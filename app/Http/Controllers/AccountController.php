<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class AccountController extends Controller
{
    public function profile()
    {
        $user = Auth::user();
        $addresses = $user->addresses()
            ->orderByDesc('is_default')
            ->get();

        // Trả về view với cả $user và $addresses
        return view('account.profile', compact('user', 'addresses'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'phone' => 'nullable|string|max:20', // THÊM VALIDATION CHO SĐT
        ]);

        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->phone = $validatedData['phone'];
        $user->save();

        return redirect()->route('account.profile')
            ->with('success', 'Đã cập nhật thông tin cá nhân thành công!');
    }

    public function password()
    {
        return view('account.password');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validatedData = $request->validate([
            'current_password' => [
                'required',
                // Kiểm tra xem mật khẩu hiện tại có đúng không
                function ($attribute, $value, $fail) use ($user) {
                    if (!Hash::check($value, $user->password)) {
                        $fail('Mật khẩu hiện tại không chính xác.');
                    }
                },
            ],
            'new_password' => [
                'required',
                'confirmed', // Phải khớp với 'new_password_confirmation'
                Password::min(8) // Yêu cầu mật khẩu mới tối thiểu 8 ký tự
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
        ]);

        // Cập nhật mật khẩu mới
        $user->password = Hash::make($validatedData['new_password']);
        $user->save();

        return redirect()->route('account.password')
            ->with('success', 'Đã thay đổi mật khẩu thành công!');
    }

    public function orders(Request $request)
    {
        $user = Auth::user();
        
        $query = $user->orders()
            ->with(['items.product', 'items.variant', 'shipment', 'payments'])
            ->withCount('items');

        // Tìm kiếm theo mã đơn hàng
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('code', 'LIKE', "%{$search}%");
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Sắp xếp
        $sort = $request->get('sort', 'newest'); // Mặc định sắp xếp theo mới nhất
        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'a-z':
                $query->orderBy('code', 'asc');
                break;
            case 'z-a':
                $query->orderBy('code', 'desc');
                break;
            case 'total_desc':
                $query->orderBy('total', 'desc');
                break;
            case 'total_asc':
                $query->orderBy('total', 'asc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        // Phân trang
        $orders = $query->paginate(10)->withQueryString();

        return view('account.orders', compact('orders'));
    }

    public function orderDetail($id)
    {
        $user = Auth::user();
        
        $order = $user->orders()
            ->with(['items.product', 'items.variant', 'shipment', 'payments', 'coupons'])
            ->findOrFail($id);

        return view('account.order-detail', compact('order'));
    }

    public function cancelOrder($id)
    {
        $user = Auth::user();
        
        $order = $user->orders()->findOrFail($id);

        // Chỉ cho phép hủy đơn ở trạng thái pending hoặc processing
        if (!in_array($order->status, ['pending', 'processing'])) {
            return back()->with('error', 'Không thể hủy đơn hàng ở trạng thái này.');
        }

        // Cập nhật trạng thái đơn hàng
        $order->update(['status' => 'cancelled']);

        // Cập nhật trạng thái payment nếu có (dùng 'failed' vì enum không có 'cancelled')
        $order->payments()->where('status', 'pending')->update(['status' => 'failed']);

        // Hoàn trả tồn kho nếu đã trừ
        foreach ($order->items as $item) {
            if ($item->variant_id) {
                $variant = $item->variant;
                if ($variant) {
                    $variant->increment('stock_quantity', $item->quantity);
                }
            } else {
                $product = $item->product;
                if ($product) {
                    $product->increment('stock', $item->quantity);
                }
            }
        }

        return back()->with('success', 'Đã hủy đơn hàng thành công.');
    }
}
