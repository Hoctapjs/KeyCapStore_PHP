@extends('layouts.app')

@section('content')
<h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Đăng nhập</h2>

<!-- Hiển thị thông báo thành công (ví dụ: sau khi reset mật khẩu) -->
@if (session('status'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
    {{ session('status') }}
</div>
@endif

<!-- Hiển thị lỗi đăng nhập (sai email/mật khẩu) -->
@error('email')
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
    {{ $message }}
</div>
@enderror

<form method="POST" action="{{ route('login') }}">
    @csrf

    <!-- Email -->
    <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
            Địa chỉ Email
        </label>
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring focus:ring-blue-300"
            id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
    </div>

    <!-- Mật khẩu -->
    <div class="mb-6">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
            Mật khẩu
        </label>
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:ring focus:ring-blue-300"
            id="password" type="password" name="password" required>
    </div>

    <!-- Ghi nhớ đăng nhập & Quên mật khẩu -->
    <div class="flex items-center justify-between mb-6">
        <label class="flex items-center text-sm text-gray-600">
            <input class="mr-2 leading-tight" type="checkbox" name="remember_me">
            <span>Ghi nhớ tôi</span>
        </label>
        <a class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800" href="{{ route('password.request') }}">
            Quên mật khẩu?
        </a>
    </div>

    <!-- Nút Đăng nhập -->
    <div class="mb-4">
        <button class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
            Đăng nhập
        </button>
    </div>

    <!-- Link Đăng ký -->
    <p class="text-center text-sm text-gray-600">
        Chưa có tài khoản?
        <a class="font-bold text-blue-500 hover:text-blue-800" href="{{ route('register.form') }}">
            Đăng ký ngay
        </a>
    </p>
</form>
@endsection