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
}
