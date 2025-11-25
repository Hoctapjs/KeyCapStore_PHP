@extends('layouts.admin')

@section('title', 'Thêm danh mục mới')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Thêm danh mục mới</h2>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Quay lại
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf

            <div class="row g-3">
                <!-- Name -->
                <div class="col-md-6">
                    <label class="form-label">Tên danh mục <span class="text-danger">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="form-control @error('name') is-invalid @enderror">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Parent Category -->
                <div class="col-md-6">
                    <label class="form-label">Danh mục cha</label>
                    <select name="parent_id" class="form-select @error('parent_id') is-invalid @enderror">
                        <option value="">-- Không có (Danh mục gốc) --</option>
                        @foreach($parentCategories as $parent)
                            <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                                {{ $parent->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('parent_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Icon -->
                <div class="col-md-6">
                    <label class="form-label">Icon (Bootstrap Icons)</label>
                    <input type="text" name="icon" value="{{ old('icon') }}" 
                           class="form-control @error('icon') is-invalid @enderror"
                           placeholder="vd: bi bi-laptop, bi bi-keyboard">
                    @error('icon')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Xem icons tại: <a href="https://icons.getbootstrap.com/" target="_blank">Bootstrap Icons</a></small>
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
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                    Hủy
                </a>
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-circle"></i> Tạo danh mục
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
