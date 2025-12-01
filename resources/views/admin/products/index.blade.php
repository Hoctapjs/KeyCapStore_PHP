@extends('layouts.admin')

@section('title', 'Quản lý sản phẩm')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Quản lý sản phẩm</h2>
    <a href="{{ route('admin.products.create') }}" class="btn btn-success">
        <i class="bi bi-plus-circle"></i> Thêm sản phẩm mới
    </a>
</div>

<div class="card">
    <div class="card-body">
        <!-- Filters -->
        <form method="GET" class="row g-3 mb-4" id="filterForm">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="brand_id" class="form-select">
                    <option value="">Tất cả thương hiệu</option>
                    @foreach($brands as $brand)
                    <option value="{{ $brand->id }}" {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                        {{ $brand->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">Tất cả trạng thái</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Nháp</option>
                    <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Lưu trữ</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Lọc</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary w-100">Reset</a>
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
                        <th>Trạng thái</th>
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
                                $totalStock = $product->display_stock;
                            @endphp
                            <span class="badge {{ $totalStock > 10 ? 'bg-success' : ($totalStock > 0 ? 'bg-warning' : 'bg-danger') }}">
                                {{ $totalStock }}
                            </span>
                            @if($product->variants->count() > 0)
                                <small class="text-muted d-block">{{ $product->variants->count() }} biến thể</small>
                            @endif
                        </td>
                        <td>
                            @if($product->status == 'active')
                            <span class="badge bg-success">Hoạt động</span>
                            @elseif($product->status == 'inactive')
                            <span class="badge bg-secondary">Tạm ngưng</span>
                            @else
                            <span class="badge bg-danger">Hết hàng</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group-vertical btn-group-sm w-100" role="group">
                                <a href="{{ route('admin.products.variants.index', $product->id) }}" 
                                   class="btn btn-info btn-sm mb-1">
                                    <i class="bi bi-grid-3x3"></i> Biến thể ({{ $product->variants->count() }})
                                </a>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin.products.edit', $product->id) }}" 
                                       class="btn btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product->id) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
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
