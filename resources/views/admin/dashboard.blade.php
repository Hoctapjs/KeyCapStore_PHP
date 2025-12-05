@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="container-fluid py-4">

    <!-- Welcome Section -->
    <div class="row mb-5">
        <div class="col-12">
            <h1 class="display-6 fw-bold mb-2">
                Chào mừng trở lại! 👋
            </h1>
            <p class="text-muted fs-5">Tổng quan hệ thống quản trị</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-5">
        <!-- Total Products -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm bg-primary text-white h-100">
                <div class="card-body text-center">
                    <i class="bi bi-box-seam fs-1 mb-3"></i>
                    <h4 class="mb-1 fw-bold">{{ number_format($totalProducts) }}</h4>
                    <small class="opacity-90">Tổng sản phẩm</small>
                </div>
            </div>
        </div>

        <!-- Total Categories -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm bg-success text-white h-100">
                <div class="card-body text-center">
                    <i class="bi bi-folder2-open fs-1 mb-3"></i>
                    <h4 class="mb-1 fw-bold">{{ $totalCategories }}</h4>
                    <small class="opacity-90">Danh mục</small>
                </div>
            </div>
        </div>

        <!-- Total Brands -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm bg-info text-white h-100">
                <div class="card-body text-center">
                    <i class="bi bi-tags fs-1 mb-3"></i>
                    <h4 class="mb-1 fw-bold">{{ $totalBrands }}</h4>
                    <small class="opacity-90">Thương hiệu</small>
                </div>
            </div>
        </div>

        <!-- Low Stock Alert -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm bg-danger text-white h-100">
                <div class="card-body text-center">
                    <i class="bi bi-exclamation-triangle fs-1 mb-3"></i>
                    <h4 class="mb-1 fw-bold">{{ $lowStockProducts }}</h4>
                    <small class="opacity-90">Sắp hết hàng</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Access Section -->
    <h4 class="mb-4 fw-bold text-dark">Quản lý nhanh</h4>
    <div class="row g-4">

        <!-- Products -->
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm h-100 text-center">
                <div class="card-body">
                    <i class="bi bi-box-seam text-primary fs-1 mb-3"></i>
                    <h5 class="card-title mb-2">Sản phẩm</h5>
                    <p class="text-muted small mb-3">Quản lý sản phẩm & biến thể</p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-primary btn-sm">Danh sách</a>
                        <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">Thêm mới</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders -->
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm h-100 text-center">
                <div class="card-body">
                    <i class="bi bi-truck text-success fs-1 mb-3"></i>
                    <h5 class="card-title mb-2">Đơn hàng</h5>
                    <p class="text-muted small mb-3">Xử lý & theo dõi đơn hàng</p>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-success btn-sm w-100">Quản lý đơn</a>
                </div>
            </div>
        </div>

        <!-- Coupons -->
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm h-100 text-center">
                <div class="card-body">
                    <i class="bi bi-ticket-perforated text-warning fs-1 mb-3"></i>
                    <h5 class="card-title mb-2">Mã giảm giá</h5>
                    <p class="text-muted small mb-3">Tạo & quản lý khuyến mãi</p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-warning btn-sm">Danh sách</a>
                        <a href="{{ route('admin.coupons.create') }}" class="btn btn-warning btn-sm">Tạo mới</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reviews -->
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm h-100 text-center">
                <div class="card-body">
                    <i class="bi bi-chat-square-text text-info fs-1 mb-3"></i>
                    <h5 class="card-title mb-2">Đánh giá</h5>
                    <p class="text-muted small mb-3">Duyệt đánh giá khách hàng</p>
                    <a href="{{ route('admin.reviews.index') }}" class="btn btn-info btn-sm w-100 text-white">Quản lý đánh giá</a>
                </div>
            </div>
        </div>

        <!-- Categories -->
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm h-100 text-center">
                <div class="card-body">
                    <i class="bi bi-folder2-open text-success fs-1 mb-3"></i>
                    <h5 class="card-title mb-2">Danh mục</h5>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-success btn-sm w-100">Quản lý</a>
                </div>
            </div>
        </div>

        <!-- Brands -->
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm h-100 text-center">
                <div class="card-body">
                    <i class="bi bi-tags text-warning fs-1 mb-3"></i>
                    <h5 class="card-title mb-2">Thương hiệu</h5>
                    <a href="{{ route('admin.brands.index') }}" class="btn btn-outline-warning btn-sm w-100">Quản lý</a>
                </div>
            </div>
        </div>

        <!-- Tags -->
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm h-100 text-center">
                <div class="card-body">
                    <i class="bi bi-hash text-secondary fs-1 mb-3"></i>
                    <h5 class="card-title mb-2">Tags</h5>
                    <a href="{{ route('admin.tags.index') }}" class="btn btn-outline-secondary btn-sm w-100">Quản lý</a>
                </div>
            </div>
        </div>

        <!-- Inventory -->
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="card border-0 shadow-sm h-100 text-center">
                <div class="card-body">
                    <i class="bi bi-inboxes text-danger fs-1 mb-3"></i>
                    <h5 class="card-title mb-2">Tồn kho</h5>
                    <a href="{{ route('admin.inventory.index') }}" class="btn btn-danger btn-sm w-100">Xem tồn kho</a>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection