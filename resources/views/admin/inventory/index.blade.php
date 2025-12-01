@extends('layouts.admin')

@section('title', 'Quản lý tồn kho')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Quản lý tồn kho</h2>
    <div>
        <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#exportModal">
            <i class="bi bi-download"></i> Xuất báo cáo
        </button>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h6>Tồn kho đầy đủ</h6>
                <h3>{{ $stats['sufficient'] ?? 0 }}</h3>
                <small>Biến thể > 10</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h6>Sắp hết hàng</h6>
                <h3>{{ $stats['low'] ?? 0 }}</h3>
                <small>Biến thể 1-10</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <h6>Hết hàng</h6>
                <h3>{{ $stats['out'] ?? 0 }}</h3>
                <small>Biến thể = 0</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h6>Tổng giá trị kho</h6>
                <h3>{{ number_format($stats['total_value'] ?? 0, 0, ',', '.') }}</h3>
                <small>VNĐ</small>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <!-- Filters -->
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm sản phẩm / SKU..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="stock_status" class="form-select">
                    <option value="">Tất cả trạng thái</option>
                    <option value="out" {{ request('stock_status') == 'out' ? 'selected' : '' }}>Hết hàng</option>
                    <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>Sắp hết</option>
                    <option value="sufficient" {{ request('stock_status') == 'sufficient' ? 'selected' : '' }}>Đầy đủ</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Lọc</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.inventory.index') }}" class="btn btn-secondary w-100">Reset</a>
            </div>
        </form>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th width="80">ID</th>
                        <th>Sản phẩm</th>
                        <th>SKU</th>
                        <th>Biến thể</th>
                        <th>Thương hiệu</th>
                        <th>Tồn kho</th>
                        <th>Đơn giá</th>
                        <th>Giá trị kho</th>
                        <th>Trạng thái</th>
                        <th width="100">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($variants as $variant)
                    <tr>
                        <td>{{ $variant->id }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($variant->product->productImages->first())
                                <img src="{{ $variant->product->productImages->first()->image_url }}" 
                                     alt="{{ $variant->product->title }}" 
                                     class="img-thumbnail me-2" 
                                     style="width: 50px; height: 50px; object-fit: cover;">
                                @endif
                                <div>
                                    <strong>{{ Str::limit($variant->product->title, 30) }}</strong>
                                    <br><small class="text-muted">{{ $variant->product->code }}</small>
                                </div>
                            </div>
                        </td>
                        <td><code>{{ $variant->sku }}</code></td>
                        <td>
                            @if($variant->option_values && is_array($variant->option_values) && count($variant->option_values) > 0)
                                @foreach($variant->option_values as $key => $value)
                                    <span class="badge bg-secondary">{{ $key }}: {{ $value }}</span>
                                @endforeach
                            @else
                                <span class="text-muted">Mặc định</span>
                            @endif
                        </td>
                        <td>{{ $variant->product->brand->name ?? 'N/A' }}</td>
                        <td>
                            <strong style="font-size: 1.2rem;">{{ $variant->stock_quantity }}</strong>
                        </td>
                        <td>{{ number_format($variant->price, 0, ',', '.') }} đ</td>
                        <td>
                            <strong>{{ number_format($variant->stock_quantity * $variant->price, 0, ',', '.') }} đ</strong>
                        </td>
                        <td>
                            @if($variant->stock_quantity == 0)
                            <span class="badge bg-danger">Hết hàng</span>
                            @elseif($variant->stock_quantity <= 10)
                            <span class="badge bg-warning">Sắp hết</span>
                            @else
                            <span class="badge bg-success">Đầy đủ</span>
                            @endif
                        </td>
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
                                <i class="bi bi-arrow-repeat"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-4">
                            <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                            <p class="text-muted mt-2">Không có dữ liệu</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($variants->hasPages())
        <div class="d-flex justify-content-center align-items-center flex-column mt-4">
            <div class="text-muted mb-2">
                Hiển thị {{ $variants->firstItem() }} - {{ $variants->lastItem() }} trong tổng số {{ $variants->total() }} biến thể
            </div>
            <div>
                {{ $variants->onEachSide(0)->appends(request()->query())->links('vendor.pagination.custom') }}
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
                    <h5 class="modal-title">Điều chỉnh tồn kho biến thể</h5>
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
