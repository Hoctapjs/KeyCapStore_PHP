@extends('layouts.admin')

@section('title', 'Thêm biến thể - ' . $product->title)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Thêm biến thể mới</h2>
    <a href="{{ route('admin.products.variants.index', $product) }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Quay lại
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.products.variants.store', $product) }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <h5 class="border-bottom pb-2">Thông tin sản phẩm</h5>
                        <p class="mb-1"><strong>Tên:</strong> {{ $product->title }}</p>
                        <p class="mb-0"><strong>Mã:</strong> {{ $product->code }}</p>
                    </div>

                    <div class="row g-3">
                        <!-- SKU -->
                        <div class="col-md-6">
                            <label class="form-label">SKU <span class="text-danger">*</span></label>
                            <input type="text" name="sku" value="{{ old('sku') }}" required
                                   class="form-control @error('sku') is-invalid @enderror"
                                   placeholder="VD: KC-001-RED-L">
                            @error('sku')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Mã định danh duy nhất cho biến thể này</small>
                        </div>

                        <!-- Price -->
                        <div class="col-md-6">
                            <label class="form-label">Giá <span class="text-danger">*</span></label>
                            <input type="number" name="price" value="{{ old('price') }}" required min="0" step="1000"
                                   class="form-control @error('price') is-invalid @enderror"
                                   placeholder="300000">
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Stock Quantity -->
                        <div class="col-md-6">
                            <label class="form-label">Số lượng tồn kho <span class="text-danger">*</span></label>
                            <input type="number" name="stock_quantity" value="{{ old('stock_quantity', 0) }}" required min="0"
                                   class="form-control @error('stock_quantity') is-invalid @enderror">
                            @error('stock_quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Dynamic Options -->
                        <div class="col-md-12">
                            <label class="form-label">Thuộc tính biến thể</label>
                            <div id="options-container">
                                <div class="option-row row g-2 mb-2">
                                    <div class="col-5">
                                        <input type="text" class="form-control" placeholder="Tên thuộc tính (VD: Màu sắc)" data-option-key>
                                    </div>
                                    <div class="col-5">
                                        <input type="text" class="form-control" placeholder="Giá trị (VD: Đỏ)" data-option-value>
                                    </div>
                                    <div class="col-2">
                                        <button type="button" class="btn btn-danger w-100 remove-option">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-secondary" id="add-option">
                                <i class="bi bi-plus-circle"></i> Thêm thuộc tính
                            </button>
                            <small class="text-muted d-block mt-2">
                                VD: Màu sắc = Đỏ, Kích thước = L, Layout = ISO
                            </small>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('admin.products.variants.index', $product) }}" class="btn btn-secondary">
                            Hủy
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Tạo biến thể
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Hướng dẫn</h5>
            </div>
            <div class="card-body">
                <h6>SKU</h6>
                <p class="small">Mã định danh duy nhất cho biến thể. Nên bao gồm mã sản phẩm + các thuộc tính.</p>
                
                <h6>Thuộc tính</h6>
                <p class="small">Các đặc điểm phân biệt biến thể này với các biến thể khác. VD: Màu sắc, Kích thước, Layout.</p>
                
                <h6>Ví dụ:</h6>
                <ul class="small mb-0">
                    <li>SKU: <code>KC-001-RED-L</code></li>
                    <li>Màu sắc: Đỏ</li>
                    <li>Kích thước: L</li>
                    <li>Giá: 300,000đ</li>
                    <li>Tồn kho: 50</li>
                </ul>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('options-container');
    const addButton = document.getElementById('add-option');
    
    // Add option row
    addButton.addEventListener('click', function() {
        const newRow = document.createElement('div');
        newRow.className = 'option-row row g-2 mb-2';
        newRow.innerHTML = `
            <div class="col-5">
                <input type="text" class="form-control" placeholder="Tên thuộc tính (VD: Màu sắc)" data-option-key>
            </div>
            <div class="col-5">
                <input type="text" class="form-control" placeholder="Giá trị (VD: Đỏ)" data-option-value>
            </div>
            <div class="col-2">
                <button type="button" class="btn btn-danger w-100 remove-option">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        `;
        container.appendChild(newRow);
    });
    
    // Remove option row
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
    
    // Collect options before submit
    const form = container.closest('form');
    form.addEventListener('submit', function(e) {
        // Remove old option inputs
        form.querySelectorAll('input[name^="option_values["]').forEach(input => input.remove());
        
        // Collect new options
        const optionRows = container.querySelectorAll('.option-row');
        optionRows.forEach(row => {
            const keyInput = row.querySelector('[data-option-key]');
            const valueInput = row.querySelector('[data-option-value]');
            
            if (keyInput.value.trim() && valueInput.value.trim()) {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = `option_values[${keyInput.value.trim()}]`;
                hiddenInput.value = valueInput.value.trim();
                form.appendChild(hiddenInput);
            }
        });
    });
});
</script>
@endpush
@endsection
