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

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h6>Tồn kho đầy đủ</h6>
                <h3>{{ $stats['sufficient'] ?? 0 }}</h3>
                <small>Sản phẩm > 10</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h6>Sắp hết hàng</h6>
                <h3>{{ $stats['low'] ?? 0 }}</h3>
                <small>Sản phẩm 1-10</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <h6>Hết hàng</h6>
                <h3>{{ $stats['out'] ?? 0 }}</h3>
                <small>Sản phẩm = 0</small>
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
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm sản phẩm..." value="{{ request('search') }}">
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
                        <th>Mã SP</th>
                        <th>Thương hiệu</th>
                        <th>Tồn kho</th>
                        <th>Đơn giá</th>
                        <th>Giá trị kho</th>
                        <th>Trạng thái</th>
                        <th width="100">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                @if($product->productImages->first())
                                <img src="{{ $product->productImages->first()->image_url }}" 
                                     alt="{{ $product->title }}" 
                                     class="img-thumbnail me-2" 
                                     style="width: 50px; height: 50px; object-fit: cover;">
                                @endif
                                <strong>{{ Str::limit($product->title, 40) }}</strong>
                            </div>
                        </td>
                        <td><code>{{ $product->product_code }}</code></td>
                        <td>{{ $product->brand->name ?? 'N/A' }}</td>
                        <td>
                            <strong style="font-size: 1.2rem;">{{ $product->stock }}</strong>
                        </td>
                        <td>{{ number_format($product->price, 0, ',', '.') }} đ</td>
                        <td>
                            <strong>{{ number_format($product->stock * $product->price, 0, ',', '.') }} đ</strong>
                        </td>
                        <td>
                            @if($product->stock == 0)
                            <span class="badge bg-danger">Hết hàng</span>
                            @elseif($product->stock <= 10)
                            <span class="badge bg-warning">Sắp hết</span>
                            @else
                            <span class="badge bg-success">Đầy đủ</span>
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-sm btn-primary" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#adjustModal"
                                    data-id="{{ $product->id }}"
                                    data-name="{{ $product->title }}"
                                    data-stock="{{ $product->stock }}">
                                <i class="bi bi-pencil-square"></i> Điều chỉnh
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4">
                            <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                            <p class="text-muted mt-2">Không có dữ liệu</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
        <div class="d-flex justify-content-center align-items-center flex-column mt-4">
            <div class="text-muted mb-2">
                Hiển thị {{ $products->firstItem() }} - {{ $products->lastItem() }} trong tổng số {{ $products->total() }} sản phẩm
            </div>
            <div>
                {{ $products->onEachSide(0)->appends(request()->query())->links('vendor.pagination.custom') }}
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Adjust Stock Modal -->
<div class="modal fade" id="adjustModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.inventory.adjust') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Điều chỉnh tồn kho</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="product_id" id="adjust_product_id">
                    <div class="mb-3">
                        <label class="form-label">Sản phẩm</label>
                        <input type="text" class="form-control" id="adjust_product_name" readonly>
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
            document.getElementById('adjust_product_id').value = button.dataset.id;
            document.getElementById('adjust_product_name').value = button.dataset.name;
            document.getElementById('adjust_current_stock').value = button.dataset.stock;
        });
    }
});
</script>
@endpush
@endsection
