@extends('layouts.admin')

@section('title', 'Thêm sản phẩm mới')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Thêm sản phẩm mới</h2>
    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Quay lại
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row g-3">
                <!-- Title -->
                <div class="col-md-12">
                    <label class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}" required
                           class="form-control @error('title') is-invalid @enderror">
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Code -->
                <div class="col-md-6">
                    <label class="form-label">Mã sản phẩm</label>
                    <input type="text" name="code" value="{{ old('code') }}"
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
                            <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('brand_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Price -->
                <div class="col-md-4">
                    <label class="form-label">Giá <span class="text-danger">*</span></label>
                    <input type="number" name="price" value="{{ old('price') }}" required step="1000" min="0"
                           class="form-control @error('price') is-invalid @enderror">
                    @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Stock -->
                <div class="col-md-4">
                    <label class="form-label">Số lượng <span class="text-danger">*</span></label>
                    <input type="number" name="stock" value="{{ old('stock', 0) }}" required min="0"
                           class="form-control @error('stock') is-invalid @enderror">
                    @error('stock')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Status -->
                <div class="col-md-4">
                    <label class="form-label">Trạng thái <span class="text-danger">*</span></label>
                    <select name="status" required class="form-select @error('status') is-invalid @enderror">
                        <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Nháp</option>
                        <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>Lưu trữ</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Categories -->
                <div class="col-md-6">
                    <label class="form-label">Danh mục</label>
                    <div class="border rounded p-3" style="max-height: 250px; overflow-y: auto;">
                        @foreach($categories as $category)
                            <div class="mb-2">
                                <div class="form-check">
                                    <input type="checkbox" name="categories[]" value="{{ $category->id }}" 
                                           {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}
                                           class="form-check-input" id="cat{{ $category->id }}">
                                    <label class="form-check-label fw-bold" for="cat{{ $category->id }}">
                                        {{ $category->name }}
                                    </label>
                                </div>
                                @if($category->children->count() > 0)
                                    <div class="ms-4 mt-1">
                                        @foreach($category->children as $child)
                                            <div class="form-check">
                                                <input type="checkbox" name="categories[]" value="{{ $child->id }}"
                                                       {{ in_array($child->id, old('categories', [])) ? 'checked' : '' }}
                                                       class="form-check-input" id="cat{{ $child->id }}">
                                                <label class="form-check-label" for="cat{{ $child->id }}">
                                                    {{ $child->name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Tags -->
                <div class="col-md-6">
                    <label class="form-label">Tags</label>
                    <div class="border rounded p-3" style="max-height: 250px; overflow-y: auto;">
                        @foreach($tags as $tag)
                            <div class="form-check form-check-inline">
                                <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                                       {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }}
                                       class="form-check-input" id="tag{{ $tag->id }}">
                                <label class="form-check-label" for="tag{{ $tag->id }}">
                                    {{ $tag->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Description -->
                <div class="col-md-12">
                    <label class="form-label">Mô tả</label>
                    <textarea name="description" rows="5" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Product Images -->
                <div class="col-md-12">
                    <label class="form-label">Hình ảnh sản phẩm</label>
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
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-circle"></i> Tạo sản phẩm
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
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
</script>
@endpush
