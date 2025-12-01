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
        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
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
                    <label class="form-label">Mã sản phẩm</label>
                    <input type="text" name="code" value="{{ old('code', $product->code) }}"
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
                        <option value="draft" {{ old('status', $product->status) == 'draft' ? 'selected' : '' }}>Bản nháp</option>
                        <option value="archived" {{ old('status', $product->status) == 'archived' ? 'selected' : '' }}>Lưu trữ</option>
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

                <!-- Current Images -->
                @if($product->productImages->count() > 0)
                <div class="col-md-12">
                    <label class="form-label">Hình ảnh hiện tại</label>
                    <div class="row g-2">
                        @foreach($product->productImages as $image)
                        <div class="col-md-2">
                            <div class="border rounded p-2 position-relative">
                                <img src="{{ $image->image_url }}" class="img-fluid rounded">
                                <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1" 
                                        onclick="deleteImage({{ $image->id }})">
                                    ×
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Upload New Images -->
                <div class="col-md-12">
                    <label class="form-label">Thêm hình ảnh mới</label>
                    <input type="file" name="images[]" multiple accept="image/*" class="form-control @error('images') is-invalid @enderror" id="productImages">
                    <small class="text-muted">Bạn có thể chọn nhiều hình ảnh cùng lúc</small>
                    @error('images')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    
                    <!-- Image Preview -->
                    <div id="imagePreview" class="row g-2 mt-2"></div>
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

<form id="deleteImageForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
// Image preview
document.getElementById('productImages').addEventListener('change', function(e) {
    const preview = document.getElementById('imagePreview');
    preview.innerHTML = '';
    
    const files = e.target.files;
    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const col = document.createElement('div');
            col.className = 'col-md-2';
            col.innerHTML = `
                <div class="border rounded p-2">
                    <img src="${e.target.result}" class="img-fluid rounded">
                    <small class="d-block text-truncate mt-1">${file.name}</small>
                </div>
            `;
            preview.appendChild(col);
        }
        
        reader.readAsDataURL(file);
    }
});

// Delete image
function deleteImage(imageId) {
    if (confirm('Bạn có chắc muốn xóa hình ảnh này?')) {
        const form = document.getElementById('deleteImageForm');
        form.action = `/admin/products/images/${imageId}`;
        form.submit();
    }
}
</script>
@endpush
