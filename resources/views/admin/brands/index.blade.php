@extends('layouts.admin')

@section('title', 'Quản lý thương hiệu')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Quản lý thương hiệu</h2>
    <a href="{{ route('admin.brands.create') }}" class="btn btn-success">
        <i class="bi bi-plus-circle"></i> Thêm thương hiệu mới
    </a>
</div>

<div class="card">
    <div class="card-body">
        <!-- Filters -->
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm thương hiệu..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="sort" class="form-select">
                    <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Cũ nhất</option>
                    <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>Tên A-Z</option>
                    <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>Tên Z-A</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Lọc</button>
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
                        <th>Tên thương hiệu</th>
                        <th>Slug</th>
                        <th>Số sản phẩm</th>
                        <th width="150">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($brands as $brand)
                    <tr>
                        <td>{{ $brand->id }}</td>
                        <td><strong>{{ $brand->name }}</strong></td>
                        <td><code>{{ $brand->slug }}</code></td>
                        <td>
                            <span class="badge bg-info">{{ $brand->products_count ?? 0 }}</span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.brands.edit', $brand->id) }}" 
                                   class="btn btn-warning btn-sm flex-fill" title="Sửa">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <form action="{{ route('admin.brands.destroy', $brand->id) }}" 
                                      method="POST" 
                                      class="flex-fill"
                                      onsubmit="return confirm('Bạn có chắc muốn xóa thương hiệu này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm w-100" title="Xóa">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">
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
