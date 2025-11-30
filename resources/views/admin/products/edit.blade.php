@extends('layouts.admin')

@section('title', 'Chỉnh sửa sản phẩm')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Chỉnh sửa sản phẩm</h2>
    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Quay lại
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.products.update', $product) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <!-- Title -->
                <div class="col-md-8">
                    <label class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $product->title) }}" required
                           class="form-control @error('title') is-invalid @enderror">
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Code -->
                <div class="col-md-4">
                    <label class="form-label">Mã sản phẩm <span class="text-danger">*</span></label>
                    <input type="text" name="code" value="{{ old('code', $product->code) }}" required
                           class="form-control @error('code') is-invalid @enderror">
                    @error('code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Brand -->
                <div class="col-md-6">
                    <label class="form-label">Thương hiệu</label>
                    <select name="brand_id" class="form-select @error('brand_id') is-invalid @enderror">
                        <option value="">-- Chọn thương hiệu --</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('brand_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Status -->
                <div class="col-md-6">
                    <label class="form-label">Trạng thái</label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                        <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>Hoạt động</option>
                        <option value="inactive" {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                        <option value="draft" {{ old('status', $product->status) == 'draft' ? 'selected' : '' }}>Bản nháp</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Description -->
                <div class="col-md-12">
                    <label class="form-label">Mô tả</label>
                    <textarea name="description" rows="6" class="form-control @error('description') is-invalid @enderror">{{ old('description', $product->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                    Hủy
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Cập nhật sản phẩm
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
