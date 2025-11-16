@extends('layouts.app')

@section('content')

<div class="row justify-content-center py-5">

    <div class="col-md-6 col-lg-5 mb-4 mb-lg-0">
        <div class="card shadow-lg border-0 rounded-3 p-4 p-sm-4">
            <div class="card-body">

                <h1 class="h4 font-weight-bold text-primary mb-4 redtitle">Tạo tài khoản</h1>

                @if ($errors->any())
                <div class="alert alert-danger py-2 px-3 small" role="alert">
                    <strong class="font-weight-bold d-block mb-1">Có lỗi xảy ra!</strong>
                    <ul class="mb-0" style="padding-left: 1.2rem;">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label font-weight-bold small" for="name">Họ và Tên</label>
                        <input class="form-control form-control-md"
                            id="name" type="text" name="name" value="{{ old('name') }}" required autofocus>
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-weight-bold small" for="email">Địa chỉ Email</label>
                        <input class="form-control form-control-md"
                            id="email" type="email" name="email" value="{{ old('email') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-weight-bold small" for="password">Mật khẩu</label>
                        <input class="form-control form-control-md"
                            id="password" type="password" name="password" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-weight-bold small" for="password_confirmation">Xác nhận Mật khẩu</label>
                        <input class="form-control form-control-md"
                            id="password_confirmation" type="password" name="password_confirmation" required>
                    </div>

                    <div class="mb-3">
                        <button class="btn btn-warning btn-md w-100 redbackground" type="submit">
                            Đăng ký
                        </button>
                    </div>

                    <p class="text-center text-muted small">
                        Đã có tài khoản?
                        <a href="{{ route('login.form') }}">
                            Đăng nhập
                        </a>
                    </p>
                </form>

            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-5 d-none d-md-block">
        <img src="{{ asset('images/keyboardgreen.png') }}"
            alt="Register Image"
            class="img-fluid rounded-3"
            style="
                width: 100%; 
                height: 100%; 
                object-fit: cover; 
             ">
    </div>

</div>
@endsection