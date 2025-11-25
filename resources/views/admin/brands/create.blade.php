@extends('layouts.admin')

@section('title', 'Thêm thương hiệu mới')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Thêm thương hiệu mới</h2>
    <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Quay lại
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row g-3">
                <!-- Name -->
                <div class="col-md-6">
                    <label class="form-label">Tên thương hiệu <span class="text-danger">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="form-control @error('name') is-invalid @enderror">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Website URL -->
                <div class="col-md-6">
                    <label class="form-label">Website</label>
                    <input type="url" name="website" value="{{ old('website') }}"
                           class="form-control @error('website') is-invalid @enderror"
                           placeholder="https://example.com">
                    @error('website')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Logo -->
                <div class="col-md-12">
                    <label class="form-label">Logo URL</label>
                    <input type="url" name="logo" value="{{ old('logo') }}"
                           class="form-control @error('logo') is-invalid @enderror"
                           placeholder="https://example.com/logo.png">
                    @error('logo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Nhập URL của logo thương hiệu</small>
                </div>

                <!-- Description -->
                <div class="col-md-12">
                    <label class="form-label">Mô tả</label>
                    <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary">
                    Hủy
                </a>
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-circle"></i> Tạo thương hiệu
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
