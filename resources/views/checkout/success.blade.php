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

    @php
    $payment = $order->payments->first();
    @endphp

    @if($payment)
    <p>Phương thức thanh toán:
        <strong>
            @switch($payment->method)
            @case('cod') Thanh toán khi nhận hàng (COD) @break
            @case('bank_transfer') Thanh toán online (VNPay) @break
            @case('momo') Ví MoMo @break
            @default {{ $payment->method }}
            @endswitch
        </strong>
    </p>

    <p>Trạng thái thanh toán:
        <strong>
            @switch($payment->status)
            @case('pending') Chờ thanh toán @break
            @case('paid') Đã thanh toán @break
            @case('failed') Thất bại @break
            @case('refunded') Đã hoàn tiền @break
            @default {{ $payment->status }}
            @endswitch
        </strong>
    </p>
    @endif

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