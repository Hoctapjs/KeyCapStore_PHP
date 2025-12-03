<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Mail\SendPasswordResetLink;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Address;

class AuthController extends Controller
{
public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed', // Cần có trường `password_confirmation`
        ]);

        // Lưu lại session_id guest trước khi login, kể cả register cũng phải lưu
        $oldSessionId = $request->session()->getId();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
        ]);

        Auth::login($user);

        \App\Models\Cart::mergeSessionCartIntoUser($user->id, $oldSessionId);

        return redirect('/');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Lưu lại session_id cũ của guest (trước khi regenerate)
        $oldSessionId = $request->session()->getId();

        if (Auth::attempt($credentials, $request->boolean('remember_me'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            // MERGE CART guest -> user
            \App\Models\Cart::mergeSessionCartIntoUser($user->id, $oldSessionId);

            if ($user->role == 'admin' || $user->role == 'staff') {
                return redirect()->intended('/admin/dashboard');
            }

            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'Thông tin đăng nhập không chính xác.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);
        $email = $request->email;

        $token = Str::random(60);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            [
                'token' => $token,
                'created_at' => Carbon::now()
            ]
        );

        $resetUrl = route('password.reset', ['token' => $token, 'email' => $email]);

        Mail::to($email)->send(new SendPasswordResetLink($resetUrl));

        return back()->with('status', 'Chúng tôi đã gửi link cấp lại mật khẩu qua email của bạn!');
    }

    public function showResetForm(Request $request, $token)
    {
        $email = $request->query('email');
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $email
        ]);
    }

    public function resetPassword(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|exists:users,email',
            'token' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $tokenRecord = DB::table('password_reset_tokens')
            ->where('email', $validated['email'])
            ->where('token', $validated['token'])
            ->first();

        if (!$tokenRecord || Carbon::parse($tokenRecord->created_at)->addMinutes(60)->isPast()) {
            return back()->withErrors(['email' => 'Token không hợp lệ hoặc đã hết hạn.'])
                ->onlyInput('email');
        }

        $user = User::where('email', $validated['email'])->first();
        if (!$user) {
            return back()->withErrors(['email' => 'Không tìm thấy người dùng.'])->onlyInput('email');
        }

        $user->password = $validated['password'];
        $user->save();

        DB::table('password_reset_tokens')->where('email', $validated['email'])->delete();

        return redirect()->route('login.form')
            ->with('status', 'Mật khẩu của bạn đã được cập nhật!');
    }
}
