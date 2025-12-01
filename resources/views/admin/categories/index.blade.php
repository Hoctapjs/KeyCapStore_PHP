@extends('layouts.admin')

@section('title', 'Quản lý danh mục')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Quản lý danh mục</h2>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-success">
        <i class="bi bi-plus-circle"></i> Thêm danh mục mới
    </a>
</div>

<div class="card">
    <div class="card-body">
        <!-- Filters -->
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm danh mục..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Tìm kiếm</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary w-100">Reset</a>
            </div>
        </form>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th width="80">ID</th>
                        <th width="100">Icon</th>
                        <th>Tên danh mục</th>
                        <th>Slug</th>
                        <th>Số sản phẩm</th>
                        <th width="150">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                    <tr>
                        <td>{{ $category->id }}</td>
                        <td>
                            @if($category->icon)
                            <i class="{{ $category->icon }}" style="font-size: 2rem;"></i>
                            @else
                            <i class="bi bi-folder" style="font-size: 2rem; color: #ccc;"></i>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $category->name }}</strong>
                            @if($category->description)
                            <br><small class="text-muted">{{ Str::limit($category->description, 50) }}</small>
                            @endif
                        </td>
                        <td><code>{{ $category->slug }}</code></td>
                        <td>
                            <span class="badge bg-info">{{ $category->products_count ?? 0 }}</span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('admin.categories.edit', $category->id) }}" 
                                   class="btn btn-warning">
                                    <i class="bi bi-pencil"></i> Sửa
                                </a>
                                <form action="{{ route('admin.categories.destroy', $category->id) }}" 
                                      method="POST" 
                                      class="d-inline"
                                      onsubmit="return confirm('Bạn có chắc muốn xóa danh mục này?')">
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
                        <td colspan="6" class="text-center py-4">
                            <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                            <p class="text-muted mt-2">Không có danh mục nào</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($categories->hasPages())
        <div class="d-flex justify-content-center align-items-center flex-column mt-4">
            <div class="text-muted mb-2">
                Hiển thị {{ $categories->firstItem() }} - {{ $categories->lastItem() }} trong tổng số {{ $categories->total() }} danh mục
            </div>
            <div>
                {{ $categories->onEachSide(0)->appends(request()->query())->links('vendor.pagination.custom') }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
