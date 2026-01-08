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

    .account-sidebar .list-group-item-action.active svg {
        color: #111827;
    }

    .order-card {
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        margin-bottom: 1rem;
        transition: all 0.3s;
    }

    .order-card:hover {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .order-header {
        background-color: #f9fafb;
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .order-body {
        padding: 1.25rem;
    }

    .status-badge {
        padding: 0.375rem 0.75rem;
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
</style>
@endpush

@section('content')
<div class="container py-4 py-md-5">

    @if (session('success'))
    <div class="alert alert-success mb-4">
        {{ session('success') }}
    </div>
    @endif

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
            @if (session('error'))
            <div class="alert alert-danger mb-4">
                {{ session('error') }}
            </div>
            @endif

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">Lịch Sử Đơn Hàng</h2>
            </div>

            {{-- Filter & Search --}}
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('account.orders') }}" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Tìm mã đơn</label>
                            <input type="text" name="search" class="form-control" 
                                   placeholder="VD: ORD-2025..." 
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Trạng thái</label>
                            <select name="status" class="form-select">
                                <option value="">Tất cả</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                                <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Đang giao</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Sắp xếp</label>
                            <select name="sort" class="form-select">
                                <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Cũ nhất</option>
                                <option value="a-z" {{ request('sort') == 'a-z' ? 'selected' : '' }}>Mã A-Z</option>
                                <option value="z-a" {{ request('sort') == 'z-a' ? 'selected' : '' }}>Mã Z-A</option>
                                <option value="total_desc" {{ request('sort') == 'total_desc' ? 'selected' : '' }}>Giá cao - thấp</option>
                                <option value="total_asc" {{ request('sort') == 'total_asc' ? 'selected' : '' }}>Giá thấp - cao</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="bi bi-funnel"></i> Lọc
                            </button>
                            <a href="{{ route('account.orders') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle"></i> Xóa
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Orders List --}}
            @if($orders->count() > 0)
                <div class="mb-3 text-muted">
                    <small>
                        Hiển thị {{ $orders->firstItem() }}-{{ $orders->lastItem() }} trong tổng số {{ $orders->total() }} đơn hàng
                    </small>
                </div>

                @foreach($orders as $order)
                <div class="order-card">
                    <div class="order-header">
                        <div>
                            <strong>Đơn hàng #{{ $order->code }}</strong>
                            <div class="text-muted small">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                        </div>
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
                    </div>
                    <div class="order-body">
                        <div class="row">
                            <div class="col-md-8">
                                <h6>Sản phẩm ({{ $order->items_count }})</h6>
                                @foreach($order->items->take(3) as $item)
                                <div class="d-flex mb-2">
                                    @if($item->product && $item->product->image_url)
                                    <img src="{{ $item->product->image_url }}" alt="{{ $item->product_name }}" 
                                         class="me-3" style="width: 60px; height: 60px; object-fit: cover; border-radius: 0.375rem;">
                                    @endif
                                    <div>
                                        <div class="fw-semibold">{{ $item->product_name }}</div>
                                        @if($item->variant_name)
                                        <div class="text-muted small">{{ $item->variant_name }}</div>
                                        @endif
                                        <div class="small">{{ number_format($item->price_snapshot) }}₫ x {{ $item->quantity }}</div>
                                    </div>
                                </div>
                                @endforeach
                                @if($order->items->count() > 3)
                                <div class="text-muted small">+ {{ $order->items->count() - 3 }} sản phẩm khác</div>
                                @endif
                            </div>
                            <div class="col-md-4 text-md-end">
                                <div class="mb-2">
                                    <strong class="text-primary" style="font-size: 1.25rem;">
                                        {{ number_format($order->total) }}₫
                                    </strong>
                                </div>
                                <div class="d-flex justify-content-md-end gap-2">
                                    <a href="{{ route('account.order.detail', $order->id) }}" class="btn btn-outline-primary btn-sm">
                                        Xem chi tiết
                                    </a>
                                    @if(in_array($order->status, ['pending', 'processing']))
                                    <form action="{{ route('account.order.cancel', $order->id) }}" method="POST" 
                                          onsubmit="return confirm('Bạn có chắc muốn hủy đơn hàng này?')" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                            Hủy đơn
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        {{-- Shipment Info --}}
                        @if($order->shipment)
                        <div class="mt-3 pt-3 border-top">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-truck me-2"></i>
                                <strong>{{ $order->shipment->carrier ?? 'Đang chuẩn bị' }}</strong>
                                @if($order->shipment->tracking_number)
                                <span class="ms-2 text-muted">Mã vận đơn: {{ $order->shipment->tracking_number }}</span>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach

                {{-- Pagination --}}
                <div class="d-flex justify-content-center mt-4">
                    {{ $orders->onEachSide(1)->appends(request()->query())->links('vendor.pagination.custom') }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-bag-x" style="font-size: 4rem; color: #ccc;"></i>
                    <h4 class="mt-3">Chưa có đơn hàng nào</h4>
                    <p class="text-muted">Hãy bắt đầu mua sắm ngay!</p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary">Mua sắm ngay</a>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
