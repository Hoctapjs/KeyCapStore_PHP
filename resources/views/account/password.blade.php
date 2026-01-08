@extends('layouts.app')

{{-- Sao chép toàn bộ CSS từ trang profile.blade.php --}}
@push('styles')
<style>
    .account-sidebar .list-group-item-action {
        border: none;
        padding: 1rem 1.25rem;
        font-weight: 500;
        color: #333;
        border-radius: 0.375rem;
        /* bo góc */
        margin-bottom: 0.5rem;
    }

    .account-sidebar .list-group-item-action.active {
        background-color: #f3f4f6;
        /* Màu nền khi active */
        color: #000;
    }

    .account-sidebar .list-group-item-action:hover {
        background-color: #f9fafb;
    }

    .account-sidebar .list-group-item-action svg {
        width: 20px;
        margin-right: 10px;
        color: #6b7281;
    }

    .account-sidebar .list-group-item-action.active svg {
        color: #111827;
    }

    /* Card styling */
    .account-card {
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
        background-color: #fff;
    }

    .account-card-header {
        background-color: #f9fafb;
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #e5e7eb;
    }

    .account-card-header h3 {
        margin: 0;
        font-size: 1.125rem;
        font-weight: 600;
    }

    .account-card-body {
        padding: 1.5rem;
    }

    /* Nút bấm màu vàng cam giống theme của bạn */
    .btn-brand {
        background-color: #f59e0b;
        /* Giống màu vàng/cam */
        color: #fff;
        font-weight: 600;
        border: none;
        padding: 0.75rem 1.25rem;
        border-radius: 0.375rem;
    }

    .btn-brand:hover {
        background-color: #d97706;
        color: #fff;
    }
</style>
@endpush


@section('content')
<div class="container py-4 py-md-5">

    {{-- Hiển thị thông báo (nếu có) ở trên cùng --}}
    @if (session('success'))
    <div class="alert alert-success mb-4">
        {{ session('success') }}
    </div>
    @endif

    @if ($errors->any())
    <div class="alert alert-danger mb-4">
        <h5 class="alert-heading">Đã xảy ra lỗi</h5>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="row">

        {{-- CỘT 1: ĐIỀU HƯỚNG (SIDEBAR) --}}
        <div class="col-lg-3 mb-4 mb-lg-0">
            <div class="list-group account-sidebar">
                {{-- Link Thông tin --}}
                <a href="{{ route('account.profile') }}" class="list-group-item list-group-item-action">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A1.5 1.5 0 0118 21.75H6a1.5 1.5 0 01-1.499-1.632z" />
                    </svg>
                    Thông Tin Cá Nhân
                </a>

                <a href="{{ route('account.orders') }}" class="list-group-item list-group-item-action">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                    </svg>
                    Lịch Sử Đơn Hàng
                </a>

                {{-- Link Đổi Mật Khẩu (Active) --}}
                <a href="{{ route('account.password') }}" class="list-group-item list-group-item-action active">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                    </svg>
                    Đổi Mật Khẩu
                </a>

                <!-- {{-- Link Sổ Địa Chỉ --}}
                <a href="{{ route('addresses.index') }}" class="list-group-item list-group-item-action">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                    </svg>
                    Sổ Địa Chỉ
                </a> -->
            </div>
        </div>

        {{-- CỘT 2: NỘI DUNG CHÍNH (ĐỔI MẬT KHẨU) --}}
        <div class="col-lg-9">

            <div class="account-card">
                <div class="account-card-header">
                    <h3>Thay Đổi Mật Khẩu</h3>
                </div>
                <div class="account-card-body">
                    <form action="{{ route('account.updatePassword') }}" method="POST">
                        @csrf

                        {{-- Mật khẩu hiện tại --}}
                        <div class="form-group">
                            <label for="current_password">Mật khẩu hiện tại:</label>
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                        </div>

                        {{-- Mật khẩu mới --}}
                        <div class="form-group">
                            <label for="new_password">Mật khẩu mới:</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                            <small class="form-text text-muted">
                                Yêu cầu tối thiểu 8 ký tự, bao gồm chữ hoa, chữ thường, số và ký tự đặc biệt.
                            </small>
                        </div>

                        {{-- Xác nhận mật khẩu mới --}}
                        <div class="form-group">
                            <label for="new_password_confirmation">Xác nhận mật khẩu mới:</label>
                            <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
                        </div>

                        <br>
                        {{-- Đổi class nút bấm --}}
                        <button type="submit" class="btn btn-brand">Thay Đổi Mật Khẩu</button>
                    </form>
                </div>
            </div> {{-- Hết card --}}

        </div>
    </div>
</div>
@endsection