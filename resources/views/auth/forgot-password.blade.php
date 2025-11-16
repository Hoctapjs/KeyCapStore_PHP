@extends('layouts.app')

@section('content')

<div class="row justify-content-center py-5">

    <div class="col-md-6 col-lg-5 mb-4 mb-lg-0">
        <div class="card shadow-lg border-0 rounded-3 p-4 p-sm-4">
            <div class="card-body">

                <h1 class="h4 font-weight-bold text-primary mb-2 redtitle">Quên mật khẩu?</h1>
                <p class="text-muted mb-4 small">Đừng lo! Hãy nhập email của bạn và chúng tôi sẽ gửi một link để reset mật khẩu.</p>

                @if (session('status'))
                <div class="alert alert-success py-2 px-3 small" role="alert">
                    {{ session('status') }}
                </div>
                @endif

                @if ($errors->any())
                <div class="alert alert-danger py-2 px-3 small" role="alert">
                    {{ $errors->first('email') }}
                </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label font-weight-bold small" for="email">Địa chỉ Email</label>
                        <input class="form-control form-control-md"
                            id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
                    </div>

                    <div class="mb-3">
                        <button class="btn btn-warning btn-md w-100 redbackground" type="submit">
                            Gửi link Reset
                        </button>
                    </div>

                    <p class="text-center text-muted small">
                        Đã nhớ lại mật khẩu?
                        <a href="{{ route('login.form') }}">
                            Đăng nhập ngay
                        </a>
                    </p>
                </form>

            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-5 d-none d-md-block">
        <img src="{{ asset('images/keyboardgreen.png') }}"
            alt="Forgot Password Image"
            class="img-fluid rounded-3"
            style="
                width: 100%; 
                height: 100%; 
                object-fit: cover; 
             ">
    </div>

</div>
@endsection