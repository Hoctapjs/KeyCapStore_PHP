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
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Mã coupon</th>
                                    <th>Loại</th>
                                    <th>Giá trị</th>
                                    <th>Đơn tối thiểu</th>
                                    <th>Giảm tối đa</th>
                                    <th>Số lần sử dụng</th>
                                    <th>Hạn sử dụng</th>
                                    <th>Trạng thái</th>
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
                                        {{ $coupon->min_order_amount ? number_format($coupon->min_order_amount, 0, ',', '.') . 'đ' : 'Không' }}
                                    </td>
                                    <td>
                                        {{ $coupon->max_discount_amount ? number_format($coupon->max_discount_amount, 0, ',', '.') . 'đ' : 'Không giới hạn' }}
                                    </td>
                                    <td>
                                        {{ $coupon->used_count ?? 0 }} / {{ $coupon->usage_limit ?? '∞' }}
                                    </td>
                                    <td>
                                        @if($coupon->valid_from && $coupon->valid_until)
                                            {{ \Carbon\Carbon::parse($coupon->valid_from)->format('d/m/Y') }} - 
                                            {{ \Carbon\Carbon::parse($coupon->valid_until)->format('d/m/Y') }}
                                        @else
                                            Không giới hạn
                                        @endif
                                    </td>
                                    <td>
                                        @if($coupon->is_active)
                                            <span class="badge bg-success">Hoạt động</span>
                                        @else
                                            <span class="badge bg-secondary">Tắt</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.coupons.edit', $coupon) }}" 
                                               class="btn btn-warning" title="Sửa">
                                                ✎
                                            </a>
                                            <form action="{{ route('admin.coupons.destroy', $coupon) }}" 
                                                  method="POST" class="d-inline" 
                                                  onsubmit="return confirm('Bạn có chắc muốn xóa coupon này?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger" title="Xóa">
                                                    🗑
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center py-4 text-muted">
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
