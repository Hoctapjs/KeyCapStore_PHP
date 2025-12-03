@extends('layouts.admin')

@section('title', 'Chỉnh sửa biến thể')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Chỉnh sửa biến thể</h2>
    <a href="{{ route('admin.products.variants.index', $product) }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Quay lại
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.products.variants.update', [$product, $variant]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <h5 class="border-bottom pb-2">Thông tin sản phẩm</h5>
                        <p class="mb-1"><strong>Tên:</strong> {{ $product->title }}</p>
                        <p class="mb-0"><strong>Mã:</strong> {{ $product->code }}</p>
                    </div>

                    <div class="row g-3">
                        <!-- SKU -->
                        <div class="col-md-6">
                            <label class="form-label">SKU <span class="text-danger">*</span></label>
                            <input type="text" name="sku" value="{{ old('sku', $variant->sku) }}" required
                                   class="form-control @error('sku') is-invalid @enderror">
                            @error('sku')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Price -->
                        <div class="col-md-6">
                            <label class="form-label">Giá <span class="text-danger">*</span></label>
                            <input type="number" name="price" value="{{ old('price', $variant->price) }}" required min="0" step="1000"
                                   class="form-control @error('price') is-invalid @enderror">
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Stock Quantity -->
                        <div class="col-md-6">
                            <label class="form-label">Số lượng tồn kho <span class="text-danger">*</span></label>
                            <input type="number" name="stock_quantity" value="{{ old('stock_quantity', $variant->stock_quantity) }}" required min="0"
                                   class="form-control @error('stock_quantity') is-invalid @enderror">
                            @error('stock_quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Variant Images Upload -->
                        <div class="col-md-6">
                            <label class="form-label">Thêm hình ảnh mới</label>
                            <input type="file" name="images[]" multiple accept="image/*" 
                                   class="form-control @error('images') is-invalid @enderror" id="variantImages">
                            <small class="text-muted">Chọn hình ảnh mới cho biến thể</small>
                            @error('images')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Current Variant Images -->
                        @if($variant->images->count() > 0)
                        <div class="col-md-12">
                            <label class="form-label">Hình ảnh hiện tại của biến thể</label>
                            <div class="row g-2">
                                @foreach($variant->images as $image)
                                <div class="col-md-2">
                                    <div class="border rounded p-2 position-relative">
                                        <img src="{{ $image->image_url }}" class="img-fluid rounded">
                                        <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1" 
                                                onclick="deleteVariantImage({{ $image->id }})">
                                            ×
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Image Preview -->
                        <div class="col-md-12">
                            <div id="imagePreview" class="row g-2"></div>
                        </div>

                        <!-- Dynamic Options -->
                        <div class="col-md-12">
                            <label class="form-label">Thuộc tính biến thể</label>
                            <div id="options-container">
                                @if($variant->option_values)
                                    @foreach($variant->option_values as $key => $value)
                                    <div class="option-row row g-2 mb-2">
                                        <div class="col-5">
                                            <input type="text" class="form-control" placeholder="Tên thuộc tính" 
                                                   data-option-key value="{{ $key }}">
                                        </div>
                                        <div class="col-5">
                                            <input type="text" class="form-control" placeholder="Giá trị" 
                                                   data-option-value value="{{ $value }}">
                                        </div>
                                        <div class="col-2">
                                            <button type="button" class="btn btn-danger w-100 remove-option">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @endforeach
                                @else
                                    <div class="option-row row g-2 mb-2">
                                        <div class="col-5">
                                            <input type="text" class="form-control" placeholder="Tên thuộc tính" data-option-key>
                                        </div>
                                        <div class="col-5">
                                            <input type="text" class="form-control" placeholder="Giá trị" data-option-value>
                                        </div>
                                        <div class="col-2">
                                            <button type="button" class="btn btn-danger w-100 remove-option">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <button type="button" class="btn btn-sm btn-secondary" id="add-option">
                                <i class="bi bi-plus-circle"></i> Thêm thuộc tính
                            </button>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('admin.products.variants.index', $product) }}" class="btn btn-secondary">
                            Hủy
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Cập nhật biến thể
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Thông tin</h5>
            </div>
            <div class="card-body">
                <p class="mb-2"><strong>ID biến thể:</strong> {{ $variant->id }}</p>
                <p class="mb-2"><strong>Ngày tạo:</strong> {{ $variant->created_at->format('d/m/Y H:i') }}</p>
                <p class="mb-0"><strong>Cập nhật:</strong> {{ $variant->updated_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('options-container');
    const addButton = document.getElementById('add-option');
    
    // Image preview
    document.getElementById('variantImages').addEventListener('change', function(e) {
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
    
    addButton.addEventListener('click', function() {
        const newRow = document.createElement('div');
        newRow.className = 'option-row row g-2 mb-2';
        newRow.innerHTML = `
            <div class="col-5">
                <input type="text" class="form-control" placeholder="Tên thuộc tính" data-option-key>
            </div>
            <div class="col-5">
                <input type="text" class="form-control" placeholder="Giá trị" data-option-value>
            </div>
            <div class="col-2">
                <button type="button" class="btn btn-danger w-100 remove-option">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        `;
        container.appendChild(newRow);
    });
    
    container.addEventListener('click', function(e) {
        if (e.target.closest('.remove-option')) {
            const row = e.target.closest('.option-row');
            if (container.children.length > 1) {
                row.remove();
            } else {
                alert('Phải có ít nhất 1 thuộc tính');
            }
        }
    });
    
    const form = container.closest('form');
    form.addEventListener('submit', function(e) {
        // Remove old hidden inputs
        form.querySelectorAll('input[name^="option_keys["]').forEach(input => input.remove());
        form.querySelectorAll('input[name^="option_values["]').forEach(input => input.remove());
        
        const optionRows = container.querySelectorAll('.option-row');
        optionRows.forEach((row, index) => {
            const keyInput = row.querySelector('[data-option-key]');
            const valueInput = row.querySelector('[data-option-value]');
            
            if (keyInput.value.trim() && valueInput.value.trim()) {
                // Create hidden inputs for option_keys[] and option_values[]
                const hiddenKeyInput = document.createElement('input');
                hiddenKeyInput.type = 'hidden';
                hiddenKeyInput.name = `option_keys[${index}]`;
                hiddenKeyInput.value = keyInput.value.trim();
                form.appendChild(hiddenKeyInput);
                
                const hiddenValueInput = document.createElement('input');
                hiddenValueInput.type = 'hidden';
                hiddenValueInput.name = `option_values[${index}]`;
                hiddenValueInput.value = valueInput.value.trim();
                form.appendChild(hiddenValueInput);
            }
        });
    });
});
</script>
@endpush

<form id="deleteVariantImageForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
function deleteVariantImage(imageId) {
    if (confirm('Bạn có chắc muốn xóa hình ảnh này?')) {
        const form = document.getElementById('deleteVariantImageForm');
        form.action = `/admin/products/images/${imageId}`;
        form.submit();
    }
}
</script>
@endsection
