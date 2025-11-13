@extends('layouts.app')

@section('content')
<h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Tạo tài khoản</h2>

<!-- Hiển thị TẤT CẢ các lỗi validation -->
@if ($errors->any())
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
    <strong class="font-bold">Có lỗi xảy ra!</strong>
    <ul>
        @foreach ($errors->all() as $error)
        <li>- {{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form method="POST" action="{{ route('register') }}">
    @csrf

    <!-- Tên -->
    <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
            Họ và Tên
        </label>
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring focus:ring-blue-300"
            id="name" type="text" name="name" value="{{ old('name') }}" required autofocus>
    </div>

    <!-- Email -->
    <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
            Địa chỉ Email
        </label>
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring focus:ring-blue-300"
            id="email" type="email" name="email" value="{{ old('email') }}" required>
    </div>

    <!-- Mật khẩu -->
    <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
            Mật khẩu
        </label>
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring focus:ring-blue-300"
            id="password" type="password" name="password" required>
    </div>

    <!-- Xác nhận Mật khẩu -->
    <div class="mb-6">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="password_confirmation">
            Xác nhận Mật khẩu
        </label>
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring focus:ring-blue-300"
            id="password_confirmation" type="password" name="password_confirmation" required>
    </div>

    <!-- Nút Đăng ký -->
    <div class="mb-4">
        <button class="w-full bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
            Đăng ký
        </button>
    </div>

    <!-- Link Đăng nhập -->
    <p class="text-center text-sm text-gray-600">
        Đã có tài khoản?
        <a class="font-bold text-blue-500 hover:text-blue-800" href="{{ route('login.form') }}">
            Đăng nhập
        </a>
    </p>
</form>
@endsection