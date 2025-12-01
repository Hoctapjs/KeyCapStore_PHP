@extends('layouts.admin')

@section('title', 'Quản lý Coupon')
@section('page-title', 'Quản lý Coupon')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Danh sách Coupon</h5>
                    <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">
                        + Thêm Coupon mới
                    </a>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <form method="GET" class="row g-3 mb-4">
                        <div class="col-md-3">
                            <input type="text" name="search" class="form-control" 
                                   placeholder="Tìm kiếm mã coupon..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="type" class="form-select">
                                <option value="">Tất cả loại</option>
                                <option value="fixed" {{ request('type') == 'fixed' ? 'selected' : '' }}>Giảm cố định</option>
                                <option value="percent" {{ request('type') == 'percent' ? 'selected' : '' }}>Giảm %</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="sort" class="form-select">
                                <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Cũ nhất</option>
                                <option value="value_high" {{ request('sort') == 'value_high' ? 'selected' : '' }}>Giá trị cao</option>
                                <option value="value_low" {{ request('sort') == 'value_low' ? 'selected' : '' }}>Giá trị thấp</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">Lọc</button>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary w-100">Reset</a>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Mã coupon</th>
                                    <th>Loại</th>
                                    <th>Giá trị</th>
                                    <th>Đơn tối thiểu</th>
                                    <th>Số lần sử dụng</th>
                                    <th>Hạn sử dụng</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($coupons as $coupon)
                                <tr>
                                    <td>{{ $coupon->id }}</td>
                                    <td><strong class="text-primary">{{ $coupon->code }}</strong></td>
                                    <td>
                                        @if($coupon->type == 'fixed')
                                            <span class="badge bg-info">Giảm cố định</span>
                                        @else
                                            <span class="badge bg-warning">Giảm %</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($coupon->type == 'fixed')
                                            {{ number_format($coupon->value, 0, ',', '.') }}đ
                                        @else
                                            {{ $coupon->value }}%
                                        @endif
                                    </td>
                                    <td>
                                        {{ number_format($coupon->min_order_total ?? 0, 0, ',', '.') }}đ
                                    </td>
                                    <td>
                                        {{ $coupon->max_uses ?? '∞' }}
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($coupon->starts_at)->format('d/m/Y') }} - 
                                        {{ \Carbon\Carbon::parse($coupon->ends_at)->format('d/m/Y') }}
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('admin.coupons.edit', $coupon) }}" 
                                               class="btn btn-warning btn-sm flex-fill" title="Sửa">
                                                <i class="bi bi-pencil-fill"></i>
                                            </a>
                                            <form action="{{ route('admin.coupons.destroy', $coupon) }}" 
                                                  method="POST" class="flex-fill" 
                                                  onsubmit="return confirm('Bạn có chắc muốn xóa coupon này?')">
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
                                    <td colspan="8" class="text-center py-4 text-muted">
                                        Chưa có coupon nào. <a href="{{ route('admin.coupons.create') }}">Tạo coupon mới</a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $coupons->links('vendor.pagination.bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
