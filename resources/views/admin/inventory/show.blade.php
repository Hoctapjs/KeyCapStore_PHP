@extends('layouts.admin')

@section('title', 'Quản lý tồn kho - ' . $product->title)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>Quản lý tồn kho</h2>
        <p class="text-muted mb-0">
            <a href="{{ route('admin.inventory.index') }}" class="text-decoration-none">Tồn kho</a> 
            / {{ $product->title }}
        </p>
    </div>
    <div>
        <a href="{{ route('admin.inventory.index') }}" class="btn btn-secondary me-2">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>
</div>

<!-- Product Info Card -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-2">
                @if($product->productImages->first())
                <img src="{{ $product->productImages->first()->image_url }}" 
                     alt="{{ $product->title }}" 
                     class="img-fluid rounded">
                @else
                <div class="bg-secondary text-white d-flex align-items-center justify-content-center rounded" style="height: 100px;">
                    No image
                </div>
                @endif
            </div>
            <div class="col-md-10">
                <h4>{{ $product->title }}</h4>
                <p class="mb-1"><strong>Mã sản phẩm:</strong> {{ $product->code }}</p>
                <p class="mb-1"><strong>Thương hiệu:</strong> {{ $product->brand->name ?? 'N/A' }}</p>
                <p class="mb-1"><strong>Tổng biến thể:</strong> {{ $product->variants->count() }}</p>
                <p class="mb-0"><strong>Tổng tồn kho:</strong> 
                    <span class="badge bg-primary">{{ $product->total_stock }}</span>
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Variants Table -->
<div class="card">
    <div class="card-body">
        @if($product->variants->count() > 0)
        <h5 class="mb-4">Chi tiết các biến thể</h5>
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th width="80">ID</th>
                        <th width="80">Ảnh</th>
                        <th>SKU</th>
                        <th>Thuộc tính</th>
                        <th>Giá</th>
                        <th>Tồn kho</th>
                        <th>Giá trị kho</th>
                        <th width="150">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($product->variants as $variant)
                    <tr>
                        <td>{{ $variant->id }}</td>
                        <td>
                            @if($variant->images->count() > 0)
                                <img src="{{ $variant->images->first()->image_url }}" 
                                     alt="{{ $variant->sku }}" 
                                     class="rounded"
                                     style="width: 50px; height: 50px; object-fit: cover;">
                                @if($variant->images->count() > 1)
                                    <span class="badge bg-info">+{{ $variant->images->count() - 1 }}</span>
                                @endif
                            @else
                                <div class="bg-light text-muted d-flex align-items-center justify-content-center rounded" 
                                     style="width: 50px; height: 50px; font-size: 10px;">
                                    No img
                                </div>
                            @endif
                        </td>
                        <td><code>{{ $variant->sku }}</code></td>
                        <td>
                            @if($variant->option_values)
                                @foreach($variant->option_values as $key => $value)
                                    <span class="badge bg-secondary">{{ $key }}: {{ $value }}</span>
                                @endforeach
                            @else
                                <span class="text-muted">Mặc định</span>
                            @endif
                        </td>
                        <td>{{ number_format($variant->price, 0, ',', '.') }}đ</td>
                        <td>
                            <span class="badge {{ $variant->stock_quantity > 10 ? 'bg-success' : ($variant->stock_quantity > 0 ? 'bg-warning' : 'bg-danger') }}">
                                {{ $variant->stock_quantity }}
                            </span>
                        </td>
                        <td>{{ number_format($variant->price * $variant->stock_quantity, 0, ',', '.') }}đ</td>
                        <td>
                            <button class="btn btn-sm btn-primary w-100" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#adjustModal"
                                    data-id="{{ $variant->id }}"
                                    data-name="{{ $variant->product->title }}"
                                    data-sku="{{ $variant->sku }}"
                                    data-options="{{ json_encode($variant->option_values) }}"
                                    data-stock="{{ $variant->stock_quantity }}"
                                    title="Cập nhật">
                                <i class="bi bi-pencil-square"></i> Nhập
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                            <p class="text-muted mt-2">Chưa có biến thể nào</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @else
        <h5 class="mb-4">Cập nhật tồn kho sản phẩm</h5>
        <div class="row">
            <div class="col-md-4">
                <div class="card bg-light">
                    <div class="card-body text-center">
                        <h6 class="text-muted">Tồn kho hiện tại</h6>
                        <h2 class="text-primary">{{ $product->stock }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <button class="btn btn-primary btn-lg w-100" 
                        data-bs-toggle="modal" 
                        data-bs-target="#adjustProductModal">
                    <i class="bi bi-pencil-square"></i> Nhập thêm hàng
                </button>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Adjust Stock Modal -->
<div class="modal fade" id="adjustModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.inventory.adjust-variant') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Nhập thêm hàng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="variant_id" id="adjust_variant_id">
                    <div class="mb-3">
                        <label class="form-label">Sản phẩm</label>
                        <input type="text" class="form-control" id="adjust_product_name" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">SKU</label>
                        <input type="text" class="form-control" id="adjust_sku" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Biến thể</label>
                        <div id="adjust_options"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tồn kho hiện tại</label>
                        <input type="text" class="form-control" id="adjust_current_stock" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Loại điều chỉnh</label>
                        <select name="type" class="form-select" required>
                            <option value="in">Nhập kho (+)</option>
                            <option value="out">Xuất kho (-)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Loại giao dịch</label>
                        <select name="movement_type" class="form-select" id="movement_type" required>
                            <option value="purchase">Nhập hàng</option>
                            <option value="sale">Bán hàng</option>
                            <option value="adjustment">Điều chỉnh</option>
                            <option value="return">Trả hàng</option>
                            <option value="manual">Thủ công</option>
                        </select>
                    </div>
                    <div class="mb-3" id="unit_cost_group">
                        <label class="form-label">Giá nhập/đơn vị (₫)</label>
                        <input type="number" name="unit_cost" class="form-control" min="0" step="0.01" placeholder="Chỉ nhập khi là nhập hàng">
                        <small class="text-muted">Dùng để tính chi phí nhập kho</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Số lượng</label>
                        <input type="number" name="quantity" class="form-control" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ghi chú</label>
                        <textarea name="note" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Xác nhận</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Adjust Product Stock Modal (for products without variants) -->
<div class="modal fade" id="adjustProductModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.inventory.update-stock', $product) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Nhập thêm hàng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Sản phẩm</label>
                        <input type="text" class="form-control" value="{{ $product->title }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mã sản phẩm</label>
                        <input type="text" class="form-control" value="{{ $product->code }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tồn kho hiện tại</label>
                        <input type="text" class="form-control" value="{{ $product->stock }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Loại điều chỉnh</label>
                        <select name="change_qty_type" class="form-select" required>
                            <option value="in">Nhập kho (+)</option>
                            <option value="out">Xuất kho (-)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Loại giao dịch</label>
                        <select name="type" class="form-select" required>
                            <option value="purchase">Nhập hàng</option>
                            <option value="sale">Bán hàng</option>
                            <option value="adjustment">Điều chỉnh</option>
                            <option value="return">Trả hàng</option>
                            <option value="manual">Thủ công</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Giá nhập/đơn vị (₫)</label>
                        <input type="number" name="unit_cost" class="form-control" min="0" step="0.01" placeholder="Chỉ nhập khi là nhập hàng">
                        <small class="text-muted">Dùng để tính chi phí nhập kho</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Số lượng</label>
                        <input type="number" name="change_qty" class="form-control" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ghi chú</label>
                        <textarea name="note" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Xác nhận</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const adjustModal = document.getElementById('adjustModal');
    if (adjustModal) {
        adjustModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            document.getElementById('adjust_variant_id').value = button.dataset.id;
            document.getElementById('adjust_product_name').value = button.dataset.name;
            document.getElementById('adjust_sku').value = button.dataset.sku;
            document.getElementById('adjust_current_stock').value = button.dataset.stock;
            
            // Display options
            const optionsDiv = document.getElementById('adjust_options');
            try {
                const options = JSON.parse(button.dataset.options);
                if (options && Object.keys(options).length > 0) {
                    let html = '';
                    for (const [key, value] of Object.entries(options)) {
                        html += `<span class="badge bg-secondary me-1">${key}: ${value}</span>`;
                    }
                    optionsDiv.innerHTML = html;
                } else {
                    optionsDiv.innerHTML = '<span class="text-muted">Mặc định</span>';
                }
            } catch(e) {
                optionsDiv.innerHTML = '<span class="text-muted">Mặc định</span>';
            }
        });
    }
});
</script>
@endpush
@endsection
