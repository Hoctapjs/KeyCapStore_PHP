@extends('layouts.admin')

@section('title', 'Chi tiết Đơn hàng #' . $order->code)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Đơn hàng #{{ $order->code }}</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.orders.edit', $order) }}" class="btn btn-warning">
                <i class="bi bi-pencil-fill me-1"></i> Sửa
            </a>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Quay lại
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Order Info -->
        <div class="col-md-8">
            <!-- Order Items -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-box me-2"></i>Sản phẩm trong đơn ({{ $order->items->count() }})</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>SKU</th>
                                    <th class="text-center">SL</th>
                                    <th class="text-end">Đơn giá</th>
                                    <th class="text-end">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($item->product && $item->product->productImages->first())
                                                <img src="{{ $item->product->productImages->first()->image_url }}" 
                                                     alt="{{ $item->title_snapshot }}" 
                                                     class="rounded me-2"
                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                            @endif
                                            <div>
                                                <div class="fw-bold">{{ $item->title_snapshot }}</div>
                                                @if($item->variant)
                                                    <small class="text-muted">
                                                        {{ implode(', ', $item->variant->option_values ?? []) }}
                                                    </small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td><code>{{ $item->sku_snapshot }}</code></td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">{{ number_format($item->price, 0, ',', '.') }}đ</td>
                                    <td class="text-end fw-bold">{{ number_format($item->total, 0, ',', '.') }}đ</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" class="text-end">Tạm tính:</td>
                                    <td class="text-end">{{ number_format($order->subtotal, 0, ',', '.') }}đ</td>
                                </tr>
                                @if($order->discount_total > 0)
                                <tr class="text-success">
                                    <td colspan="4" class="text-end">Giảm giá:</td>
                                    <td class="text-end">-{{ number_format($order->discount_total, 0, ',', '.') }}đ</td>
                                </tr>
                                @endif
                                <tr>
                                    <td colspan="4" class="text-end">Phí vận chuyển:</td>
                                    <td class="text-end">{{ number_format($order->shipping_fee, 0, ',', '.') }}đ</td>
                                </tr>
                                @if($order->tax_total > 0)
                                <tr>
                                    <td colspan="4" class="text-end">Thuế:</td>
                                    <td class="text-end">{{ number_format($order->tax_total, 0, ',', '.') }}đ</td>
                                </tr>
                                @endif
                                <tr class="table-primary">
                                    <td colspan="4" class="text-end fw-bold">Tổng cộng:</td>
                                    <td class="text-end fw-bold fs-5 text-danger">{{ number_format($order->total, 0, ',', '.') }}đ</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Shipping Address -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-geo-alt me-2"></i>Địa chỉ giao hàng</h5>
                </div>
                <div class="card-body">
                    @if($order->shipping_address)
                        <p class="mb-1"><strong>{{ $order->shipping_address['name'] ?? 'N/A' }}</strong></p>
                        <p class="mb-1">{{ $order->shipping_address['phone'] ?? 'N/A' }}</p>
                        <p class="mb-0">
                            {{ $order->shipping_address['address'] ?? '' }}, 
                            {{ $order->shipping_address['ward'] ?? '' }}, 
                            {{ $order->shipping_address['district'] ?? '' }}, 
                            {{ $order->shipping_address['city'] ?? '' }}
                        </p>
                    @else
                        <p class="text-muted mb-0">Không có thông tin</p>
                    @endif
                </div>
            </div>

            <!-- Note -->
            @if($order->note)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-chat-text me-2"></i>Ghi chú</h5>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $order->note }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Order Status -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Thông tin đơn hàng</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td class="text-muted">Mã đơn:</td>
                            <td class="fw-bold">{{ $order->code }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Ngày đặt:</td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Trạng thái:</td>
                            <td>
                                @php
                                    $statusClasses = [
                                        'pending' => 'warning',
                                        'processing' => 'info',
                                        'shipped' => 'primary',
                                        'completed' => 'success',
                                        'cancelled' => 'danger',
                                    ];
                                    $statusLabels = [
                                        'pending' => 'Chờ xử lý',
                                        'processing' => 'Đang xử lý',
                                        'shipped' => 'Đang giao',
                                        'completed' => 'Hoàn thành',
                                        'cancelled' => 'Đã hủy',
                                    ];
                                @endphp
                                <span class="badge bg-{{ $statusClasses[$order->status] ?? 'secondary' }} fs-6">
                                    {{ $statusLabels[$order->status] ?? $order->status }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Customer Info -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-person me-2"></i>Khách hàng</h5>
                </div>
                <div class="card-body">
                    @if($order->user)
                        <p class="mb-1"><strong>{{ $order->user->name }}</strong></p>
                        <p class="mb-1"><i class="bi bi-envelope me-1"></i> {{ $order->user->email }}</p>
                        @if($order->user->phone)
                            <p class="mb-0"><i class="bi bi-telephone me-1"></i> {{ $order->user->phone }}</p>
                        @endif
                    @else
                        <p class="text-muted mb-0">Khách vãng lai</p>
                    @endif
                </div>
            </div>

            <!-- Coupons Used -->
            @if($order->coupons->count() > 0)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-ticket-perforated me-2"></i>Mã giảm giá đã dùng</h5>
                </div>
                <div class="card-body">
                    @foreach($order->coupons as $coupon)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-success">{{ $coupon->code }}</span>
                            <span class="text-success">-{{ number_format($coupon->pivot->amount, 0, ',', '.') }}đ</span>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Payment Info -->
            @if($order->payments->count() > 0)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-credit-card me-2"></i>Thanh toán</h5>
                </div>
                <div class="card-body">
                    @foreach($order->payments as $payment)
                        <div class="mb-2">
                            <div class="d-flex justify-content-between">
                                <span>{{ ucfirst($payment->method) }}</span>
                                <span class="badge bg-{{ $payment->status == 'completed' ? 'success' : 'warning' }}">
                                    {{ $payment->status }}
                                </span>
                            </div>
                            <small class="text-muted">{{ number_format($payment->amount, 0, ',', '.') }}đ</small>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Shipment Info -->
            @if($order->shipment)
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-truck me-2"></i>Vận chuyển</h5>
                </div>
                <div class="card-body">
                    <p class="mb-1"><strong>{{ $order->shipment->carrier }}</strong></p>
                    @if($order->shipment->tracking_number)
                        <p class="mb-1">Mã vận đơn: <code>{{ $order->shipment->tracking_number }}</code></p>
                    @endif
                    <span class="badge bg-{{ $order->shipment->status == 'delivered' ? 'success' : 'info' }}">
                        {{ $order->shipment->status }}
                    </span>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
