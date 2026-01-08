@extends('layouts.app')

@push('styles')
<style>
    .account-sidebar .list-group-item-action {
        border: none;
        padding: 1rem 1.25rem;
        font-weight: 500;
        color: #333;
        border-radius: 0.375rem;
        margin-bottom: 0.5rem;
    }

    .account-sidebar .list-group-item-action.active {
        background-color: #f3f4f6;
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

    .order-detail-card {
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        background-color: #fff;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
        margin-bottom: 1.5rem;
    }

    .order-detail-header {
        background-color: #f9fafb;
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #e5e7eb;
    }

    .order-detail-body {
        padding: 1.5rem;
    }

    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        font-weight: 600;
    }

    .status-pending {
        background-color: #fef3c7;
        color: #92400e;
    }

    .status-processing {
        background-color: #dbeafe;
        color: #1e40af;
    }

    .status-shipped {
        background-color: #ddd6fe;
        color: #5b21b6;
    }

    .status-completed {
        background-color: #d1fae5;
        color: #065f46;
    }

    .status-cancelled {
        background-color: #fee2e2;
        color: #991b1b;
    }

    .status-paid {
        background-color: #d1fae5;
        color: #065f46;
    }
</style>
@endpush

@section('content')
<div class="container py-4 py-md-5">

    <div class="row">

        {{-- SIDEBAR --}}
        <div class="col-lg-3 mb-4 mb-lg-0">
            <div class="list-group account-sidebar">
                <a href="{{ route('account.profile') }}" class="list-group-item list-group-item-action">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A1.5 1.5 0 0118 21.75H6a1.5 1.5 0 01-1.499-1.632z" />
                    </svg>
                    Thông Tin Cá Nhân
                </a>

                <a href="{{ route('account.orders') }}" class="list-group-item list-group-item-action active">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                    </svg>
                    Lịch Sử Đơn Hàng
                </a>

                <a href="{{ route('account.password') }}" class="list-group-item list-group-item-action">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                    </svg>
                    Đổi Mật Khẩu
                </a>
            </div>
        </div>

        {{-- MAIN CONTENT --}}
        <div class="col-lg-9">
            @if (session('success'))
            <div class="alert alert-success mb-4">
                {{ session('success') }}
            </div>
            @endif

            @if (session('error'))
            <div class="alert alert-danger mb-4">
                {{ session('error') }}
            </div>
            @endif

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <a href="{{ route('account.orders') }}" class="btn btn-outline-secondary btn-sm mb-2">
                        <i class="bi bi-arrow-left me-1"></i> Quay lại
                    </a>
                    <h2 class="mb-0">Chi tiết đơn hàng #{{ $order->code }}</h2>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="status-badge status-{{ $order->status }}">
                        @switch($order->status)
                            @case('pending') Chờ xử lý @break
                            @case('processing') Đang xử lý @break
                            @case('shipped') Đang giao @break
                            @case('completed') Hoàn thành @break
                            @case('cancelled') Đã hủy @break
                            @case('paid') Đã thanh toán @break
                            @default {{ $order->status }}
                        @endswitch
                    </span>
                    
                    @if(in_array($order->status, ['pending', 'processing']))
                    <form action="{{ route('account.order.cancel', $order->id) }}" method="POST" 
                          onsubmit="return confirm('Bạn có chắc muốn hủy đơn hàng này?')">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="bi bi-x-circle me-1"></i> Hủy đơn
                        </button>
                    </form>
                    @endif
                </div>
            </div>

            {{-- Order Info --}}
            <div class="order-detail-card">
                <div class="order-detail-header">
                    <h5 class="mb-0">Thông tin đơn hàng</h5>
                </div>
                <div class="order-detail-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Mã đơn hàng:</strong> {{ $order->code }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Shipping Address --}}
            <div class="order-detail-card">
                <div class="order-detail-header">
                    <h5 class="mb-0"><i class="bi bi-geo-alt me-2"></i>Địa chỉ giao hàng</h5>
                </div>
                <div class="order-detail-body">
                    @if($order->shipping_address)
                        <p class="mb-1"><strong>{{ $order->shipping_address['full_name'] ?? 'N/A' }}</strong></p>
                        <p class="mb-1">{{ $order->shipping_address['phone'] ?? 'N/A' }}</p>
                        <p class="mb-0">{{ $order->shipping_address['address'] ?? 'N/A' }}</p>
                        @if(isset($order->shipping_address['note']) && $order->shipping_address['note'])
                        <p class="mb-0 text-muted mt-2">Ghi chú: {{ $order->shipping_address['note'] }}</p>
                        @endif
                    @else
                        <p class="text-muted">Chưa có thông tin địa chỉ</p>
                    @endif
                </div>
            </div>

            {{-- Shipment Info --}}
            @if($order->shipment)
            <div class="order-detail-card">
                <div class="order-detail-header">
                    <h5 class="mb-0"><i class="bi bi-truck me-2"></i>Thông tin vận chuyển</h5>
                </div>
                <div class="order-detail-body">
                    <p class="mb-1"><strong>{{ $order->shipment->carrier }}</strong></p>
                    @if($order->shipment->tracking_number)
                        <p class="mb-1">Mã vận đơn: <code>{{ $order->shipment->tracking_number }}</code></p>
                    @endif
                    <span class="badge bg-{{ $order->shipment->status == 'delivered' ? 'success' : 'info' }}">
                        {{ $order->shipment->status }}
                    </span>
                    @if($order->shipment->shipped_at)
                        <p class="mb-0 mt-2 text-muted">Ngày giao: {{ $order->shipment->shipped_at->format('d/m/Y H:i') }}</p>
                    @endif
                    @if($order->shipment->delivered_at)
                        <p class="mb-0 text-muted">Ngày nhận: {{ $order->shipment->delivered_at->format('d/m/Y H:i') }}</p>
                    @endif
                </div>
            </div>
            @endif

            {{-- Products --}}
            <div class="order-detail-card">
                <div class="order-detail-header">
                    <h5 class="mb-0">Sản phẩm ({{ $order->items->count() }})</h5>
                </div>
                <div class="order-detail-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th class="text-center">Số lượng</th>
                                    <th class="text-end">Đơn giá</th>
                                    <th class="text-end">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex">
                                            @if($item->product && $item->product->image_url)
                                            <img src="{{ $item->product->image_url }}" alt="{{ $item->product_name }}" 
                                                 class="me-3" style="width: 60px; height: 60px; object-fit: cover; border-radius: 0.375rem;">
                                            @endif
                                            <div>
                                                <div class="fw-semibold">{{ $item->product_name }}</div>
                                                @if($item->variant_name)
                                                <div class="text-muted small">{{ $item->variant_name }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">{{ number_format($item->price_snapshot) }}₫</td>
                                    <td class="text-end fw-semibold">{{ number_format($item->price_snapshot * $item->quantity) }}₫</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="row justify-content-end">
                        <div class="col-md-5">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tạm tính:</span>
                                <strong>{{ number_format($order->subtotal) }}₫</strong>
                            </div>
                            @if($order->discount_total > 0)
                            <div class="d-flex justify-content-between mb-2 text-success">
                                <span>Giảm giá:</span>
                                <strong>-{{ number_format($order->discount_total) }}₫</strong>
                            </div>
                            @endif
                            @if($order->shipping_fee > 0)
                            <div class="d-flex justify-content-between mb-2">
                                <span>Phí vận chuyển:</span>
                                <strong>{{ number_format($order->shipping_fee) }}₫</strong>
                            </div>
                            @endif
                            <hr>
                            <div class="d-flex justify-content-between mb-0">
                                <span class="h5 mb-0">Tổng cộng:</span>
                                <strong class="h4 text-primary mb-0">{{ number_format($order->total) }}₫</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Payment Info --}}
            <div class="order-detail-card">
                <div class="order-detail-header">
                    <h5 class="mb-0"><i class="bi bi-credit-card me-2"></i>Thanh toán</h5>
                </div>
                <div class="order-detail-body">
                    @if($order->payments && $order->payments->count() > 0)
                        @foreach($order->payments as $payment)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <strong>
                                        @switch($payment->method)
                                            @case('cod') Thanh toán khi nhận hàng @break
                                            @case('bank_transfer') Chuyển khoản ngân hàng (VNPay) @break
                                            @case('momo') MoMo @break
                                            @default {{ $payment->method }}
                                        @endswitch
                                    </strong>
                                    <div class="text-muted small">{{ number_format($payment->amount) }}₫</div>
                                </div>
                                <span class="badge bg-{{ $payment->status == 'paid' ? 'success' : ($payment->status == 'failed' ? 'danger' : 'warning') }}">
                                    {{ $payment->status }}
                                </span>
                            </div>
                            @if($payment->transaction_id)
                            <div class="text-muted small mt-1">Mã giao dịch: {{ $payment->transaction_id }}</div>
                            @endif
                            @if($payment->paid_at)
                            <div class="text-muted small">Thanh toán lúc: {{ $payment->paid_at->format('d/m/Y H:i') }}</div>
                            @endif
                        </div>
                        @endforeach
                    @else
                        <p class="text-muted mb-0">Chưa có thông tin thanh toán</p>
                    @endif
                </div>
            </div>

        </div>

    </div>
</div>
@endsection
