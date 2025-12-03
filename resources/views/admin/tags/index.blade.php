@extends('layouts.admin')

@section('title', 'Quản lý Tags')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Quản lý Tags</h1>
        <a href="{{ route('admin.tags.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle-fill me-1"></i> Thêm Tag
        </a>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-6">
                    <input type="text" name="search" class="form-control" 
                           placeholder="Tìm kiếm tag..." value="{{ request('search') }}">
                </div>
                <div class="col-md-4">
                    <select name="sort" class="form-select" onchange="this.form.submit()">
                        <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Cũ nhất</option>
                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Tên A-Z</option>
                        <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Tên Z-A</option>
                        <option value="products_desc" {{ request('sort') == 'products_desc' ? 'selected' : '' }}>Nhiều sản phẩm nhất</option>
                        <option value="products_asc" {{ request('sort') == 'products_asc' ? 'selected' : '' }}>Ít sản phẩm nhất</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-outline-primary w-100">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tags Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên Tag</th>
                            <th>Slug</th>
                            <th>Số sản phẩm</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tags as $tag)
                        <tr>
                            <td>{{ $tag->id }}</td>
                            <td>
                                <span class="badge bg-secondary fs-6">{{ $tag->name }}</span>
                            </td>
                            <td><code>{{ $tag->slug }}</code></td>
                            <td>
                                <span class="badge bg-info">{{ $tag->products_count }} sản phẩm</span>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.tags.edit', $tag) }}" 
                                       class="btn btn-sm btn-warning" title="Sửa">
                                        <i class="bi bi-pencil-fill"></i>
                                    </a>
                                    <form action="{{ route('admin.tags.destroy', $tag) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Bạn có chắc muốn xóa tag này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Xóa">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                                Không có tag nào
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $tags->withQueryString()->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
