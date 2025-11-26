<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Coupon;
use App\Models\CouponRedemption;
use App\Models\Order;
use App\Models\OrderItem;
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
            $order->items()->create([
                'product_id'     => $item->product_id,
                'variant_id'     => $item->variant_id,
                'title_snapshot' => $item->product->title,
                'sku_snapshot'   => $item->variant->sku ?? $item->product->sku ?? null,
                'price'          => $item->price_snapshot,
                'quantity'       => $item->quantity,
                'total'          => $item->price_snapshot * $item->quantity,
            ]);
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

        // 8. Clear giỏ
        $cart->items()->delete();

        // 9. Redirect tới trang success
        return redirect()->route('checkout.success', $order)
            ->with('success', 'Đặt hàng thành công!');
    }


    public function success(Order $order)
    {
        // Có thể check thêm order thuộc về user hiện tại hay không
        if (Auth::check() && $order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('checkout.success', compact('order'));
    }
}
