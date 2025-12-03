<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Coupon;
use App\Models\CouponRedemption;
use App\Models\InventoryMovement;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Services\VnPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    private function getCart()
    {
        if (Auth::check()) {
            $cart = Cart::firstOrCreate([
                'user_id' => Auth::id(),
            ]);
        } else {
            $sessionId = session()->getId();
            $cart = Cart::firstOrCreate([
                'session_id' => $sessionId,
            ]);
        }

        return $cart->load(['items.product', 'items.variant']);
    }

    public function index()
    {
        $cart = $this->getCart();

        if ($cart->items->count() === 0) {
            return redirect()->route('cart.index')
                ->with('error', 'Giỏ hàng đang trống.');
        }

        $subtotal = $cart->items->sum(fn($i) => $i->price_snapshot * $i->quantity);

        return view('checkout.index', compact('cart', 'subtotal'));
    }

    public function placeOrder(Request $request)
    {
        $cart = $this->getCart();

        if ($cart->items->count() === 0) {
            return redirect()->route('cart.index')
                ->with('error', 'Giỏ hàng đang trống.');
        }

        // 1. Validate thông tin
        $data = $request->validate([
            'full_name'    => 'required|string|max:255',
            'phone'        => 'required|string|max:20',
            'address'      => 'required|string|max:500',
            'note'         => 'nullable|string',
            'coupon_code'  => 'nullable|string|max:50',
            'payment_method' => 'required|in:cod,vnpay,momo',
        ]);

        $cartItems = $cart->items;

        // 2. Tính subtotal từ cart
        $subtotal = $cartItems->sum(fn($item) => $item->price_snapshot * $item->quantity);

        // -----------------------------
        // 3. XỬ LÝ COUPON (NẾU CÓ)
        // -----------------------------
        $coupon        = null;
        $discountTotal = 0;

        if (!empty($data['coupon_code'])) {
            $code   = trim($data['coupon_code']);
            $coupon = Coupon::where('code', $code)->first();

            if (!$coupon) {
                return back()
                    ->withErrors(['coupon_code' => 'Mã giảm giá không tồn tại.'])
                    ->withInput();
            }

            $now = now();

            // chưa bắt đầu
            if ($coupon->starts_at && $coupon->starts_at->isFuture()) {
                return back()
                    ->withErrors(['coupon_code' => 'Mã giảm giá này chưa bắt đầu áp dụng.'])
                    ->withInput();
            }

            // đã hết hạn
            if ($coupon->ends_at && $coupon->ends_at->isPast()) {
                return back()
                    ->withErrors(['coupon_code' => 'Mã giảm giá này đã hết hạn.'])
                    ->withInput();
            }

            // chưa đủ min_order_total
            if ($coupon->min_order_total && $subtotal < $coupon->min_order_total) {
                return back()
                    ->withErrors(['coupon_code' => 'Đơn hàng chưa đạt giá trị tối thiểu để dùng mã này.'])
                    ->withInput();
            }

            // max_uses toàn hệ thống
            if ($coupon->max_uses) {
                $totalUsed = $coupon->redemptions()->count();
                if ($totalUsed >= $coupon->max_uses) {
                    return back()
                        ->withErrors(['coupon_code' => 'Mã giảm giá này đã được sử dụng tối đa số lần cho phép.'])
                        ->withInput();
                }
            }

            // per_user_limit cho user hiện tại
            if (Auth::check() && $coupon->per_user_limit) {
                $userUsed = $coupon->redemptions()
                    ->where('user_id', Auth::id())
                    ->count();

                if ($userUsed >= $coupon->per_user_limit) {
                    return back()
                        ->withErrors(['coupon_code' => 'Bạn đã sử dụng mã này tối đa số lần cho phép.'])
                        ->withInput();
                }
            }

            // TÍNH SỐ TIỀN GIẢM
            // Giả sử: type = 'percent' hoặc 'fixed'
            if ($coupon->type === 'percent' || $coupon->type === 'percentage') {
                // $discountTotal = round($subtotal * ($coupon->value / 100)); -> làm tròn vầy là lỗ chit mịa luôn
                $discountTotal = (int) floor($subtotal * ($coupon->value / 100)); // làm tròn xuống (này hỏi ý lại nhóm xem làm tròn lên hay xún)
            } else { // 'fixed'
                $discountTotal = (float) $coupon->value;
            }

            // Không cho giảm quá subtotal
            if ($discountTotal > $subtotal) {
                $discountTotal = $subtotal;
            }
        }

        // 4. Các phần phí khác (tạm để 0)
        $shippingFee = 0;
        $taxTotal    = 0;

        $total = $subtotal - $discountTotal + $shippingFee + $taxTotal;

        // 5. Tạo Order
        $order = Order::create([
            'user_id'          => Auth::id(),
            'code'             => 'ORD-' . now()->format('YmdHis') . '-' . rand(1000, 9999),
            'status'           => 'pending',
            'subtotal'         => $subtotal,
            'discount_total'   => $discountTotal,
            'shipping_fee'     => $shippingFee,
            'tax_total'        => $taxTotal,
            'total'            => $total,
            'shipping_address' => [
                'full_name' => $data['full_name'],
                'phone'     => $data['phone'],
                'address'   => $data['address'],
            ],
            'billing_address'  => [
                'full_name' => $data['full_name'],
                'phone'     => $data['phone'],
                'address'   => $data['address'],
            ],
            'note'             => $data['note'] ?? null,
        ]);

        // 6. OrderItems từ CartItems
        foreach ($cartItems as $item) {
            // 6.1 Tạo order item
            $order->items()->create([
                'product_id'     => $item->product_id,
                'variant_id'     => $item->variant_id,
                'title_snapshot' => $item->product->title,
                // products table không có 'sku', chỉ có 'code' ⇒ dùng code
                'sku_snapshot'   => $item->variant->sku ?? $item->product->code ?? null,
                'price'          => $item->price_snapshot,
                'quantity'       => $item->quantity,
                'total'          => $item->price_snapshot * $item->quantity,
            ]);

            // 6.2 Trừ tồn kho + ghi log inventory
            if ($item->variant_id) {
                // Trừ ở variant
                $variant = $item->variant; // đã load từ getCart()
                if ($variant) {
                    $variant->stock_quantity = max(0, $variant->stock_quantity - $item->quantity);
                    $variant->save();

                    InventoryMovement::create([
                        'product_id' => $item->product_id,
                        'variant_id' => $item->variant_id,
                        'change_qty' => -$item->quantity,
                        'reason'     => 'order',
                        'note'       => 'Order ' . $order->code,
                    ]);
                }
            } else {
                // Trừ ở product
                $product = $item->product;
                if ($product) {
                    $product->stock = max(0, $product->stock - $item->quantity);
                    $product->save();

                    InventoryMovement::create([
                        'product_id' => $item->product_id,
                        'variant_id' => null,
                        'change_qty' => -$item->quantity,
                        'reason'     => 'order',
                        'note'       => 'Order ' . $order->code,
                    ]);
                }
            }
        }

        // 7. Lưu lại việc dùng coupon (nếu có)
        if ($coupon && $discountTotal > 0) {
            // order_coupons
            $order->orderCoupons()->create([
                'coupon_id' => $coupon->id,
                'amount'    => $discountTotal,
            ]);

            // coupon_redemptions
            CouponRedemption::create([
                'coupon_id' => $coupon->id,
                'user_id'   => Auth::id(),
                'order_id'  => $order->id,
                'used_at'   => now(),
                'amount'    => $discountTotal,
            ]);
        }

        // 8. Tạo record thanh toán (payments)
        $paymentMethod = $data['payment_method'] ?? 'cod';

        // Lưu ý: enum method hiện tại: 'cod','bank_transfer','paypal','stripe','momo','zalopay'
        // Nếu bạn muốn thêm 'vnpay' đúng nghĩa, sau này chỉnh lại enum migration.
        // Tạm thời: dùng 'bank_transfer' cho VNPay, 'momo' cho MoMo.

        $payment = Payment::create([
            'order_id'       => $order->id,
            'method'         => $paymentMethod === 'vnpay' ? 'bank_transfer' : $paymentMethod,
            'amount'         => $total,
            'status'         => 'pending', // chờ thanh toán
            'transaction_id' => null,
            'raw_payload'    => null,
            'paid_at'        => null,
        ]);

        // 9. Tuỳ theo phương thức thanh toán
        if ($paymentMethod === 'cod') {
            // COD: coi như tạo đơn thành công, chưa thu tiền
            // Bạn có thể để status 'pending' hoặc 'processing' tuỳ quy ước
            $order->update(['status' => 'pending']);

            // Clear giỏ
            $cart->items()->delete();

            return redirect()->route('checkout.success', $order)
                ->with('success', 'Đặt hàng thành công! Thanh toán khi nhận hàng.');
        }

        if ($paymentMethod === 'vnpay') {
            // VNPay: redirect sang cổng thanh toán
            $vnpUrl = VnPayService::createPaymentUrl(
                $order,
                $payment->id,
                $request->ip()
            );

            // Không clear giỏ ngay, chờ thanh toán thành công rồi hãy clear trong callback
            return redirect()->away($vnpUrl);
        }

        if ($paymentMethod === 'momo') {
            // TODO: tích hợp MoMo sau (flow tương tự VNPay: tạo payment, build URL, redirect)
            // Tạm thời báo chưa hỗ trợ:
            return back()->withErrors([
                'payment_method' => 'Thanh toán MoMo hiện chưa được kích hoạt.'
            ]);
        }

        // 8. Clear giỏ
        // $cart->items()->delete();

        // 9. Redirect tới trang success
        // return redirect()->route('checkout.success', $order)
        //     ->with('success', 'Đặt hàng thành công!');
    }


    public function success(Order $order)
    {
        // Có thể check thêm order thuộc về user hiện tại hay không
        if (Auth::check() && $order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('checkout.success', compact('order'));
    }

    public function vnpayReturn(Request $request)
    {
        $vnpData = $request->all();

        \Log::info('VNPay return', $vnpData);

        $hashSecret = config('vnpay.vnp_hash_secret');

        // Lấy tất cả các tham số bắt đầu bằng vnp_
        $inputData = [];
        foreach ($vnpData as $key => $value) {
            if (strpos($key, 'vnp_') === 0) {
                $inputData[$key] = $value;
            }
        }

        $vnpSecureHash = $inputData['vnp_SecureHash'] ?? '';
        unset($inputData['vnp_SecureHash'], $inputData['vnp_SecureHashType']);

        ksort($inputData);

        $hashDataArr = [];
        foreach ($inputData as $key => $value) {
            $hashDataArr[] = urlencode($key) . '=' . urlencode($value);
        }
        $hashData = implode('&', $hashDataArr);

        $myHash = hash_hmac('sha512', $hashData, $hashSecret);

        if (strcasecmp($myHash, $vnpSecureHash) !== 0) {
            // Sai chữ ký => về /checkout
            return redirect()->route('checkout.index')
                ->with('error', 'Không xác thực được chữ ký VNPay.');
        }

        // Đến đây là chữ ký OK, không còn sai nữa nên trả về trang thanh toán thành công
        $paymentId     = $vnpData['vnp_TxnRef'] ?? null;
        $responseCode  = $vnpData['vnp_ResponseCode'] ?? '99';
        $transactionNo = $vnpData['vnp_TransactionNo'] ?? null;

        $payment = Payment::find($paymentId);
        if (!$payment) {
            return redirect()->route('home')
                ->with('error', 'Không tìm thấy giao dịch thanh toán.');
        }

        $order = $payment->order;
        if (!$order) {
            return redirect()->route('home')
                ->with('error', 'Không tìm thấy đơn hàng.');
        }

        if ($responseCode === '00') {
            // THÀNH CÔNG
            $payment->update([
                'status'         => 'paid',
                'transaction_id' => $transactionNo,
                'raw_payload'    => $vnpData,
                'paid_at'        => now(),
            ]);

            $order->update(['status' => 'paid']);

            if (Auth::check()) {
                $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
                $cart->items()->delete();
            }

            return redirect()->route('checkout.success', $order)
                ->with('success', 'Thanh toán VNPay thành công!');
        } else {
            // THẤT BẠI / HỦY
            $payment->update([
                'status'      => 'failed',
                'raw_payload' => $vnpData,
            ]);

            return redirect()->route('checkout.index')
                ->with('error', 'Thanh toán VNPay thất bại hoặc bị huỷ. Vui lòng thử lại.');
        }
    }
}
