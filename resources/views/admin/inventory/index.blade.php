@extends('layouts.admin')

@section('title', 'Quản lý tồn kho')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Quản lý tồn kho</h2>
    <div>
        <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#exportModal">
            <i class="bi bi-download"></i> Xuất báo cáo
        </button>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h6>Tồn kho đầy đủ</h6>
                <h3>{{ $stats['sufficient'] ?? 0 }}</h3>
                <small>Biến thể > 10</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h6>Sắp hết hàng</h6>
                <h3>{{ $stats['low'] ?? 0 }}</h3>
                <small>Biến thể 1-10</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <h6>Hết hàng</h6>
                <h3>{{ $stats['out'] ?? 0 }}</h3>
                <small>Biến thể = 0</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h6>Tổng giá trị kho</h6>
                <h3>{{ number_format($stats['total_value'] ?? 0, 0, ',', '.') }}</h3>
                <small>VNĐ</small>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <!-- Filters -->
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm sản phẩm..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Lọc</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.inventory.index') }}" class="btn btn-secondary w-100">Reset</a>
            </div>
        </form>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th width="80">ID</th>
                        <th width="100">Hình ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th>Thương hiệu</th>
                        <th>Giá</th>
                        <th>Tồn kho</th>
                        <th>Biến thể</th>
                        <th width="150">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>
                            @if($product->productImages->first())
                            <img src="{{ $product->productImages->first()->image_url }}" 
                                 alt="{{ $product->title }}" 
                                 class="img-thumbnail" 
                                 style="width: 60px; height: 60px; object-fit: cover;">
                            @else
                            <div class="bg-secondary text-white d-flex align-items-center justify-content-center" 
                                 style="width: 60px; height: 60px;">
                                No image
                            </div>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $product->title }}</strong><br>
                            <small class="text-muted">{{ $product->code }}</small>
                        </td>
                        <td>{{ $product->brand->name ?? 'N/A' }}</td>
                        <td>
                            @if($product->variants->count() > 0)
                                {{ number_format($product->min_price, 0, ',', '.') }}đ
                                @if($product->min_price != $product->max_price)
                                    - {{ number_format($product->max_price, 0, ',', '.') }}đ
                                @endif
                            @else
                                {{ number_format($product->price, 0, ',', '.') }}đ
                            @endif
                        </td>
                        <td>
                            @php
                                $totalStock = $product->variants->count() > 0 ? $product->total_stock : $product->stock;
                            @endphp
                            <span class="badge {{ $totalStock > 10 ? 'bg-success' : ($totalStock > 0 ? 'bg-warning' : 'bg-danger') }}">
                                {{ $totalStock }}
                            </span>
                        </td>
                        <td>
                            @if($product->variants->count() > 0)
                                <span class="badge bg-info">{{ $product->variants->count() }}</span>
                            @else
                                <span class="badge bg-secondary">0</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.inventory.show', $product->id) }}" 
                               class="btn btn-primary btn-sm w-100" title="Nhập kho">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                            <p class="text-muted mt-2">Không có sản phẩm nào</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
        <div class="d-flex justify-content-center align-items-center flex-column mt-4">
            <div class="text-muted mb-2">
                Hiển thị {{ $products->firstItem() }} - {{ $products->lastItem() }} trong tổng số {{ $products->total() }} sản phẩm
            </div>
            <div>
                {{ $products->onEachSide(0)->appends(request()->query())->links('vendor.pagination.custom') }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
