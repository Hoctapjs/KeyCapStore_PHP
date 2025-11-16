@extends('layouts.app')

@section('content')

<div class="row justify-content-center py-5">

    <div class="col-lg-6 mb-4 mb-lg-0">
        <div class="card shadow-lg border-0 rounded-3 p-4 p-sm-5">
            <div class="card-body">

                <h1 class="h3 font-weight-bold text-primary mb-2 redtitle">Login</h1>
                <h2 class="h4 font-weight-bold text-dark mb-2">Welcome back!</h2>
                <!-- <p class="text-muted mb-4">Enter your email address and password</p> -->

                @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
                @endif

                @error('email')
                <div class="alert alert-danger" role="alert">
                    {{ $message }}
                </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label font-weight-bold" for="email">Email address</label>
                        <input class="form-control form-control-lg"
                            id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-weight-bold" for="password">Password</label>
                        <input class="form-control form-control-lg"
                            id="password" type="password" name="password" required autocomplete="current-password">
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember_me" id="rememberMeCheckbox">
                            <label class="form-check-label" for="rememberMeCheckbox">
                                Ghi nhớ tôi
                            </label>
                        </div>
                        <a href="{{ route('password.request') }}">
                            Quên mật khẩu?
                        </a>
                    </div>

                    <div class="mb-3">
                        <button class="btn btn-primary btn-lg w-100 redbackground" type="submit">
                            Login
                        </button>
                    </div>

                    <p class="text-center text-muted small">
                        Chưa có tài khoản?
                        <a href="{{ route('register.form') }}">
                            Đăng ký ngay
                        </a>
                    </p>
                </form>

            </div>
        </div>
    </div>

    <div class="col-lg-6 d-none d-lg-block">
        <img src="{{ asset('images/keyboardgreen.png') }}"
            alt="Login Image"
            class="img-fluid rounded-3"
            style="
                width: 100%; 
                height: 100%; 
                object-fit: cover; /* Đảm bảo ảnh lấp đầy khung */
             ">
    </div>

</div>
@endsection