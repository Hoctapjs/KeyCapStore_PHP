@extends('layouts.admin')

@section('title', 'Quản lý biến thể - ' . $product->title)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>Quản lý biến thể</h2>
        <p class="text-muted mb-0">
            <a href="{{ route('admin.products.index') }}" class="text-decoration-none">Sản phẩm</a> 
            / {{ $product->title }}
        </p>
    </div>
    <div>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary me-2">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
        <a href="{{ route('admin.products.variants.create', $product) }}" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Thêm biến thể mới
        </a>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<!-- Product Info Card -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-2">
                @if($product->productImages->first())
                <img src="{{ $product->productImages->first()->image_url }}" 
                     alt="{{ $product->title }}" 
                     class="img-fluid rounded">
                @else
                <div class="bg-secondary text-white d-flex align-items-center justify-content-center rounded" style="height: 100px;">
                    No image
                </div>
                @endif
            </div>
            <div class="col-md-10">
                <h4>{{ $product->title }}</h4>
                <p class="mb-1"><strong>Mã sản phẩm:</strong> {{ $product->code }}</p>
                <p class="mb-1"><strong>Thương hiệu:</strong> {{ $product->brand->name ?? 'N/A' }}</p>
                <p class="mb-1"><strong>Tổng biến thể:</strong> {{ $product->variants->count() }}</p>
                <p class="mb-0"><strong>Tổng tồn kho:</strong> 
                    <span class="badge bg-primary">{{ $product->total_stock }}</span>
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Variants Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th width="80">ID</th>
                        <th>SKU</th>
                        <th>Thuộc tính</th>
                        <th>Giá</th>
                        <th>Tồn kho</th>
                        <th>Giá trị kho</th>
                        <th width="150">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($variants as $variant)
                    <tr>
                        <td>{{ $variant->id }}</td>
                        <td><code>{{ $variant->sku }}</code></td>
                        <td>
                            @if($variant->option_values)
                                @foreach($variant->option_values as $key => $value)
                                    <span class="badge bg-secondary">{{ $key }}: {{ $value }}</span>
                                @endforeach
                            @else
                                <span class="text-muted">Mặc định</span>
                            @endif
                        </td>
                        <td>{{ number_format($variant->price, 0, ',', '.') }}đ</td>
                        <td>
                            <span class="badge {{ $variant->stock_quantity > 10 ? 'bg-success' : ($variant->stock_quantity > 0 ? 'bg-warning' : 'bg-danger') }}">
                                {{ $variant->stock_quantity }}
                            </span>
                        </td>
                        <td>{{ number_format($variant->price * $variant->stock_quantity, 0, ',', '.') }}đ</td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('admin.products.variants.edit', [$product, $variant]) }}" 
                                   class="btn btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.products.variants.destroy', [$product, $variant]) }}" 
                                      method="POST" 
                                      class="d-inline"
                                      onsubmit="return confirm('Bạn có chắc muốn xóa biến thể này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                            <p class="text-muted mt-2">Chưa có biến thể nào</p>
                            <a href="{{ route('admin.products.variants.create', $product) }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Thêm biến thể đầu tiên
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($variants->hasPages())
        <div class="d-flex justify-content-center align-items-center flex-column mt-4">
            <div class="text-muted mb-2">
                Hiển thị {{ $variants->firstItem() }} - {{ $variants->lastItem() }} trong tổng số {{ $variants->total() }} biến thể
            </div>
            <div>
                {{ $variants->onEachSide(0)->links('vendor.pagination.custom') }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
