@extends('layouts.admin')

@section('title', 'Quản lý thương hiệu')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Quản lý thương hiệu</h2>
    <a href="{{ route('admin.brands.create') }}" class="btn btn-success">
        <i class="bi bi-plus-circle"></i> Thêm thương hiệu mới
    </a>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="card">
    <div class="card-body">
        <!-- Filters -->
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm thương hiệu..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Tìm kiếm</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary w-100">Reset</a>
            </div>
        </form>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th width="80">ID</th>
                        <th width="100">Logo</th>
                        <th>Tên thương hiệu</th>
                        <th>Slug</th>
                        <th>Website</th>
                        <th>Số sản phẩm</th>
                        <th width="150">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($brands as $brand)
                    <tr>
                        <td>{{ $brand->id }}</td>
                        <td>
                            @if($brand->logo)
                            <img src="{{ $brand->logo }}" 
                                 alt="{{ $brand->name }}" 
                                 class="img-thumbnail" 
                                 style="width: 60px; height: 60px; object-fit: contain; background: white;">
                            @else
                            <div class="bg-secondary text-white d-flex align-items-center justify-content-center" 
                                 style="width: 60px; height: 60px;">
                                <i class="bi bi-image"></i>
                            </div>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $brand->name }}</strong>
                            @if($brand->description)
                            <br><small class="text-muted">{{ Str::limit($brand->description, 50) }}</small>
                            @endif
                        </td>
                        <td><code>{{ $brand->slug }}</code></td>
                        <td>
                            @if($brand->website)
                            <a href="{{ $brand->website }}" target="_blank" class="text-decoration-none">
                                <i class="bi bi-link-45deg"></i> {{ Str::limit($brand->website, 30) }}
                            </a>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $brand->products_count ?? 0 }}</span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('admin.brands.edit', $brand->id) }}" 
                                   class="btn btn-warning">
                                    <i class="bi bi-pencil"></i> Sửa
                                </a>
                                <form action="{{ route('admin.brands.destroy', $brand->id) }}" 
                                      method="POST" 
                                      class="d-inline"
                                      onsubmit="return confirm('Bạn có chắc muốn xóa thương hiệu này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="bi bi-trash"></i> Xóa
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                            <p class="text-muted mt-2">Không có thương hiệu nào</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($brands->hasPages())
        <div class="d-flex justify-content-center align-items-center flex-column mt-4">
            <div class="text-muted mb-2">
                Hiển thị {{ $brands->firstItem() }} - {{ $brands->lastItem() }} trong tổng số {{ $brands->total() }} thương hiệu
            </div>
            <div>
                {{ $brands->onEachSide(0)->appends(request()->query())->links('vendor.pagination.custom') }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
