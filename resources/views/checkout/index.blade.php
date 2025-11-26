@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">Thanh toán</h2>

    <div class="row">
        {{-- Cột trái: thông tin giao hàng --}}
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    Thông tin giao hàng
                </div>
                <div class="card-body">
                    <form action="{{ route('checkout.place') }}" method="POST" id="checkout-form">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Họ và tên</label>
                            <input type="text" name="full_name" class="form-control"
                                value="{{ old('full_name', auth()->user()->name ?? '') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Số điện thoại</label>
                            <input type="text" name="phone" class="form-control"
                                value="{{ old('phone') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Địa chỉ giao hàng</label>
                            <textarea name="address" rows="3" class="form-control" required>{{ old('address') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ghi chú</label>
                            <textarea name="note" rows="3" class="form-control">{{ old('note') }}</textarea>
                        </div>

                        <!-- {{-- Nếu sau này dùng mã giảm giá:
                        <div class="mb-3">
                            <label class="form-label">Mã giảm giá</label>
                            <input type="text" name="coupon_code" class="form-control" value="{{ old('coupon_code') }}">
                </div>
                --}} -->
                        <div class="mb-3">
                            <label class="form-label">Mã giảm giá</label>
                            <input
                                type="text"
                                name="coupon_code"
                                class="form-control"
                                value="{{ old('coupon_code') }}"
                                placeholder="Nhập mã nếu có">
                            @error('coupon_code')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Cột phải: tóm tắt đơn hàng --}}
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    Đơn hàng của bạn
                </div>
                <div class="card-body">
                    <ul class="list-group mb-3">
                        @foreach($cart->items as $item)
                        <li class="list-group-item d-flex justify-content-between">
                            <div>
                                <div>{{ $item->product->title }}</div>
                                @if($item->variant)
                                <small class="text-muted">{{ $item->variant->name }}</small><br>
                                @endif
                                <small class="text-muted">SL: {{ $item->quantity }}</small>
                            </div>
                            <span>
                                {{ number_format($item->price_snapshot * $item->quantity, 0, ',', '.') }}₫
                            </span>
                        </li>
                        @endforeach

                        <li class="list-group-item d-flex justify-content-between">
                            <span>Tạm tính</span>
                            <strong>{{ number_format($subtotal, 0, ',', '.') }}₫</strong>
                        </li>
                    </ul>

                    <button type="submit" form="checkout-form" class="btn btn-success w-100">
                        Xác nhận đặt hàng
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection