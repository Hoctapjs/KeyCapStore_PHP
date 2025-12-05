@extends('layouts.admin')
@section('title', 'Quản lý Mã Giảm Giá')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h1 class="h3 mb-0">Quản lý Mã Giảm Giá</h1>
    </div>

    {{-- Statistics --}}
    <div class="row mb-4 g-3">
        <div class="col-xl-2 col-md-4 col-6">
            <div class="card border-0 shadow-sm bg-primary text-white h-100">
                <div class="card-body text-center text-white">
                    <h4 class="mb-1 fw-bold">{{ $stats['total'] }}</h4>
                    <small class="opacity-90">Tổng mã giảm giá</small>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
            <div class="card border-0 shadow-sm bg-success text-white h-100">
                <div class="card-body text-center text-white">
                    <h4 class="mb-1 fw-bold">{{ $stats['active'] }}</h4>
                    <small class="opacity-90">Đang hoạt động</small>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
            <div class="card border-0 shadow-sm bg-danger text-white h-100">
                <div class="card-body text-center text-white">
                    <h4 class="mb-1 fw-bold">{{ $stats['expired'] }}</h4>
                    <small class="opacity-90">Đã hết hạn</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Search + Filters --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tìm kiếm mã</label>
                    <input type="text" name="search" class="form-control"
                           placeholder="Nhập mã coupon..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Loại coupon</label>
                    <select name="type" class="form-select">
                        <option value="">Tất cả</option>
                        <option value="percent" {{ request('type') == 'percent' ? 'selected' : '' }}>Giảm theo %</option>
                        <option value="fixed" {{ request('type') == 'fixed' ? 'selected' : '' }}>Giảm cố định</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Sắp xếp</label>
                    <select name="sort" class="form-select">
                        <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Cũ nhất</option>
                        <option value="value_high" {{ request('sort') == 'value_high' ? 'selected' : '' }}>Giá trị cao → thấp</option>
                        <option value="value_low" {{ request('sort') == 'value_low' ? 'selected' : '' }}>Giá trị thấp → cao</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        Lọc
                    </button>
                </div>
                <div class="col-md-12 text-end">
                    @if(request()->hasAny(['search', 'type', 'sort']))
                        <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary">
                            Reset bộ lọc
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- Data List --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Danh sách mã giảm giá</h5>
            <a href="{{ route('admin.coupons.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle me-1"></i> Thêm Coupon
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="60">ID</th>
                            <th>Mã</th>
                            <th>Loại</th>
                            <th>Giá trị</th>
                            <th>Đơn tối thiểu</th>
                            <th>Sử dụng</th>
                            <th>Hiệu lực</th>
                            <th>Trạng thái</th>
                            <th width="120">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($coupons as $coupon)
                            <tr>
                                <td class="text-muted">#{{ $coupon->id }}</td>
                                <td><strong class="text-primary">{{ $coupon->code }}</strong></td>
                                <td>
                                    <span class="badge {{ $coupon->type == 'percent' ? 'bg-warning' : 'bg-info' }}">
                                        {{ $coupon->type == 'percent' ? 'Giảm %' : 'Cố định' }}
                                    </span>
                                </td>
                                <td>
                                    @if($coupon->type == 'percent')
                                        <strong class="text-danger">{{ $coupon->value }}%</strong>
                                    @else
                                        <strong class="text-success">{{ number_format($coupon->value) }}đ</strong>
                                    @endif
                                </td>
                                <td>{{ number_format($coupon->min_order_total ?? 0) }}đ</td>
                                <td>
                                    {{ $coupon->redemptions->count() }} /
                                    <span class="fw-bold">{{ $coupon->max_uses ?? '∞' }}</span>
                                </td>
                                <td>
                                    {{ $coupon->starts_at?->format('d/m/Y') }} →<br>
                                    {{ $coupon->ends_at?->format('d/m/Y') }}
                                </td>
                                <td>
                                    @if(now()->between($coupon->starts_at, $coupon->ends_at))
                                        <span class="badge bg-success">Đang hoạt động</span>
                                    @else
                                        <span class="badge bg-secondary">Hết hạn</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('admin.coupons.edit', $coupon) }}"
                                           class="btn btn-warning btn-sm" title="Sửa">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST"
                                              onsubmit="return confirm('Xóa coupon này?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Xóa">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5 text-muted">
                                    <i class="bi bi-ticket-perforated fs-1 text-muted d-block mb-3"></i>
                                    <p class="text-muted">Chưa có mã giảm giá nào</p>
                                    <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">
                                        Tạo coupon đầu tiên
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $coupons->appends(request()->query())->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection