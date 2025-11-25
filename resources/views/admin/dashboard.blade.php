@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="container-fluid py-5">
<div class="row mb-4">
    <div class="col-12">
        <h2 class="mb-1">Chào mừng trở lại! 👋</h2>
        <p class="text-muted">Tổng quan hệ thống quản lý</p>
    </div>
</div>    <!-- Statistics Cards -->
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Tổng sản phẩm</h6>
                            <h2 class="mb-0">{{ $totalProducts }}</h2>
                        </div>
                        <div class="text-primary">
                            <svg width="48" height="48" fill="currentColor">
                                <use xlink:href="#box"></use>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Danh mục</h6>
                            <h2 class="mb-0">{{ $totalCategories }}</h2>
                        </div>
                        <div class="text-success">
                            <svg width="48" height="48" fill="currentColor">
                                <use xlink:href="#folder"></use>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Thương hiệu</h6>
                            <h2 class="mb-0">{{ $totalBrands }}</h2>
                        </div>
                        <div class="text-warning">
                            <svg width="48" height="48" fill="currentColor">
                                <use xlink:href="#tag"></use>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm border-danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Sắp hết hàng</h6>
                            <h2 class="mb-0 text-danger">{{ $lowStockProducts }}</h2>
                        </div>
                        <div class="text-danger">
                            <svg width="48" height="48" fill="currentColor">
                                <use xlink:href="#exclamation-triangle"></use>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="mb-4">Quản lý nhanh</h3>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <svg width="24" height="24" fill="currentColor" class="me-2">
                            <use xlink:href="#box"></use>
                        </svg>
                        Quản lý sản phẩm
                    </h5>
                    <p class="card-text text-muted">Thêm, sửa, xóa sản phẩm và quản lý variants</p>
                    <div class="d-flex gap-2 mt-3">
                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-primary">Danh sách</a>
                        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">Thêm mới</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <svg width="24" height="24" fill="currentColor" class="me-2">
                            <use xlink:href="#folder"></use>
                        </svg>
                        Quản lý danh mục
                    </h5>
                    <p class="card-text text-muted">Tổ chức danh mục sản phẩm theo cấp bậc</p>
                    <div class="d-flex gap-2 mt-3">
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-success">Danh sách</a>
                        <a href="{{ route('admin.categories.create') }}" class="btn btn-success">Thêm mới</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <svg width="24" height="24" fill="currentColor" class="me-2">
                            <use xlink:href="#tag"></use>
                        </svg>
                        Quản lý thương hiệu
                    </h5>
                    <p class="card-text text-muted">Quản lý các thương hiệu sản phẩm</p>
                    <div class="d-flex gap-2 mt-3">
                        <a href="{{ route('admin.brands.index') }}" class="btn btn-outline-warning">Danh sách</a>
                        <a href="{{ route('admin.brands.create') }}" class="btn btn-warning">Thêm mới</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <svg width="24" height="24" fill="currentColor" class="me-2">
                            <use xlink:href="#clipboard-check"></use>
                        </svg>
                        Quản lý tồn kho
                    </h5>
                    <p class="card-text text-muted">Theo dõi và cập nhật số lượng tồn kho</p>
                    <div class="d-flex gap-2 mt-3">
                        <a href="{{ route('admin.inventory.index') }}" class="btn btn-outline-info">Xem tồn kho</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
