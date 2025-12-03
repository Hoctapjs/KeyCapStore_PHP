@extends('layouts.admin')

@section('title', 'Sửa Đơn hàng #' . $order->code)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Sửa Đơn hàng #{{ $order->code }}</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-info">
                <i class="bi bi-eye me-1"></i> Xem chi tiết
            </a>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i> Quay lại
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Cập nhật đơn hàng</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label for="status" class="form-label">Trạng thái đơn hàng <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                @foreach($statuses as $value => $label)
                                    <option value="{{ $value }}" {{ old('status', $order->status) == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="note" class="form-label">Ghi chú</label>
                            <textarea class="form-control @error('note') is-invalid @enderror" 
                                      id="note" name="note" rows="4">{{ old('note', $order->note) }}</textarea>
                            @error('note')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i> Cập nhật
                            </button>
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Hủy</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Order Summary -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Tóm tắt đơn hàng</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td class="text-muted">Mã đơn:</td>
                            <td class="fw-bold">{{ $order->code }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Khách hàng:</td>
                            <td>{{ $order->user?->name ?? 'Khách vãng lai' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Ngày đặt:</td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Số sản phẩm:</td>
                            <td>{{ $order->items->count() }} SP</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Tổng tiền:</td>
                            <td class="fw-bold text-danger">{{ number_format($order->total, 0, ',', '.') }}đ</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Order Items -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Sản phẩm</h5>
                </div>
                <div class="card-body">
                    @foreach($order->items as $item)
                        <div class="d-flex mb-3">
                            @if($item->product && $item->product->productImages->first())
                                <img src="{{ $item->product->productImages->first()->image_url }}" 
                                     alt="{{ $item->title_snapshot }}" 
                                     class="rounded me-2"
                                     style="width: 40px; height: 40px; object-fit: cover;">
                            @endif
                            <div class="flex-grow-1">
                                <div class="small fw-bold">{{ Str::limit($item->title_snapshot, 30) }}</div>
                                <div class="small text-muted">
                                    {{ $item->quantity }} x {{ number_format($item->price, 0, ',', '.') }}đ
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
