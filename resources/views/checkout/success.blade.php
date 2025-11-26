@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">Đặt hàng thành công</h2>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <p>Mã đơn hàng: <strong>{{ $order->code }}</strong></p>

    <p>Tạm tính: {{ number_format($order->subtotal, 0, ',', '.') }}₫</p>

    @if($order->discount_total > 0)
    <p>Giảm giá: -{{ number_format($order->discount_total, 0, ',', '.') }}₫</p>
    @endif

    @if($order->shipping_fee > 0)
    <p>Phí vận chuyển: {{ number_format($order->shipping_fee, 0, ',', '.') }}₫</p>
    @endif

    <p><strong>Tổng thanh toán: {{ number_format($order->total, 0, ',', '.') }}₫</strong></p>

    @if($order->orderCoupons->count())
    <p>
        Mã đã sử dụng:
        @foreach($order->orderCoupons as $oc)
        <strong>{{ $oc->coupon->code }}</strong>
        (giảm {{ number_format($oc->amount, 0, ',', '.') }}₫)
        @endforeach
    </p>
    @endif

    <a href="{{ route('home') }}" class="btn btn-primary mt-3">Tiếp tục mua sắm</a>
</div>
@endsection