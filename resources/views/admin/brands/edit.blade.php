@extends('layouts.admin')

@section('title', 'Chỉnh sửa thương hiệu')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Chỉnh sửa thương hiệu</h2>
    <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Quay lại
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.brands.update', $brand) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <!-- Name -->
                <div class="col-md-6">
                    <label class="form-label">Tên thương hiệu <span class="text-danger">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $brand->name) }}" required
                           class="form-control @error('name') is-invalid @enderror">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Logo URL -->
                <div class="col-md-6">
                    <label class="form-label">Logo URL</label>
                    <input type="url" name="logo_url" value="{{ old('logo_url', $brand->logo_url) }}"
                           class="form-control @error('logo_url') is-invalid @enderror"
                           placeholder="https://example.com/logo.png">
                    @error('logo_url')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Description -->
                <div class="col-md-12">
                    <label class="form-label">Mô tả</label>
                    <textarea name="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description', $brand->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary">
                    Hủy
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Cập nhật thương hiệu
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
