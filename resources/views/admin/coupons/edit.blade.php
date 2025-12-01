@extends('layouts.admin')

@section('title', 'Chỉnh sửa Coupon')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Chỉnh sửa Coupon</h2>
    <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Quay lại
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.coupons.update', $coupon) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <!-- Code -->
                <div class="col-md-6">
                    <label class="form-label">Mã Coupon <span class="text-danger">*</span></label>
                    <input type="text" name="code" value="{{ old('code', $coupon->code) }}" required
                           class="form-control @error('code') is-invalid @enderror"
                           placeholder="VD: SUMMER2025">
                    @error('code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Type -->
                <div class="col-md-6">
                    <label class="form-label">Loại giảm giá <span class="text-danger">*</span></label>
                    <select name="type" required class="form-select @error('type') is-invalid @enderror">
                        <option value="">-- Chọn loại --</option>
                        <option value="fixed" {{ old('type', $coupon->type) == 'fixed' ? 'selected' : '' }}>Giảm cố định (VNĐ)</option>
                        <option value="percent" {{ old('type', $coupon->type) == 'percent' ? 'selected' : '' }}>Giảm phần trăm (%)</option>
                    </select>
                    @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Value -->
                <div class="col-md-6">
                    <label class="form-label">Giá trị <span class="text-danger">*</span></label>
                    <input type="number" name="value" value="{{ old('value', $coupon->value) }}" required min="0" step="0.01"
                           class="form-control @error('value') is-invalid @enderror"
                           placeholder="VD: 50000 hoặc 10">
                    @error('value')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Nhập số tiền (VNĐ) hoặc % tùy theo loại</small>
                </div>

                <!-- Min Order Total -->
                <div class="col-md-6">
                    <label class="form-label">Đơn hàng tối thiểu</label>
                    <input type="number" name="min_order_total" value="{{ old('min_order_total', $coupon->min_order_total) }}" min="0"
                           class="form-control @error('min_order_total') is-invalid @enderror"
                           placeholder="0">
                    @error('min_order_total')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Giá trị đơn hàng tối thiểu để áp dụng</small>
                </div>

                <!-- Max Uses -->
                <div class="col-md-6">
                    <label class="form-label">Số lần sử dụng tối đa</label>
                    <input type="number" name="max_uses" value="{{ old('max_uses', $coupon->max_uses) }}" min="1"
                           class="form-control @error('max_uses') is-invalid @enderror">
                    @error('max_uses')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Per User Limit -->
                <div class="col-md-6">
                    <label class="form-label">Giới hạn mỗi người dùng</label>
                    <input type="number" name="per_user_limit" value="{{ old('per_user_limit', $coupon->per_user_limit) }}" min="1"
                           class="form-control @error('per_user_limit') is-invalid @enderror">
                    @error('per_user_limit')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Starts At -->
                <div class="col-md-6">
                    <label class="form-label">Ngày bắt đầu</label>
                    <input type="datetime-local" name="starts_at" 
                           value="{{ old('starts_at', $coupon->starts_at ? \Carbon\Carbon::parse($coupon->starts_at)->format('Y-m-d\TH:i') : '') }}"
                           class="form-control @error('starts_at') is-invalid @enderror">
                    @error('starts_at')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Ends At -->
                <div class="col-md-6">
                    <label class="form-label">Ngày kết thúc</label>
                    <input type="datetime-local" name="ends_at" 
                           value="{{ old('ends_at', $coupon->ends_at ? \Carbon\Carbon::parse($coupon->ends_at)->format('Y-m-d\TH:i') : '') }}"
                           class="form-control @error('ends_at') is-invalid @enderror">
                    @error('ends_at')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">
                    Hủy
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Cập nhật Coupon
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
