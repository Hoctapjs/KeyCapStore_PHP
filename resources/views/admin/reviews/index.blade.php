@extends('layouts.admin')

@section('title', 'Quản lý đánh giá')
@section('page-title', 'Quản lý đánh giá')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Danh sách đánh giá</h5>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <form method="GET" class="row g-3 mb-4">
                        <div class="col-md-3">
                            <input type="text" name="search" class="form-control" 
                                   placeholder="Tìm kiếm..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="status" class="form-select">
                                <option value="">Tất cả trạng thái</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Đã duyệt</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Từ chối</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="rating" class="form-select">
                                <option value="">Tất cả sao</option>
                                @for($i = 5; $i >= 1; $i--)
                                    <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>{{ $i }} sao</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">Lọc</button>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary w-100">Reset</a>
                        </div>
                    </form>

                    <!-- Reviews Table -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Sản phẩm</th>
                                    <th>Người dùng</th>
                                    <th>Đánh giá</th>
                                    <th>Nội dung</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày tạo</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reviews as $review)
                                <tr>
                                    <td>{{ $review->id }}</td>
                                    <td>
                                        <a href="{{ route('products.show', $review->product->slug) }}" target="_blank">
                                            {{ Str::limit($review->product->title, 30) }}
                                        </a>
                                    </td>
                                    <td>{{ $review->user->name }}</td>
                                    <td>
                                        <span class="text-warning">
                                            @for($i = 1; $i <= 5; $i++)
                                                {{ $i <= $review->rating ? '★' : '☆' }}
                                            @endfor
                                        </span>
                                    </td>
                                    <td>
                                        <strong>{{ $review->title }}</strong><br>
                                        <small class="text-muted">{{ Str::limit($review->content, 50) }}</small>
                                    </td>
                                    <td>
                                        @if($review->status == 'approved')
                                            <span class="badge bg-success">Đã duyệt</span>
                                        @elseif($review->status == 'pending')
                                            <span class="badge bg-warning">Chờ duyệt</span>
                                        @else
                                            <span class="badge bg-danger">Từ chối</span>
                                        @endif
                                    </td>
                                    <td>{{ $review->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            @if($review->status != 'approved')
                                            <form action="{{ route('admin.reviews.approve', $review) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-success" title="Duyệt">
                                                    ✓
                                                </button>
                                            </form>
                                            @endif
                                            
                                            @if($review->status != 'rejected')
                                            <form action="{{ route('admin.reviews.reject', $review) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-warning" title="Từ chối">
                                                    ✗
                                                </button>
                                            </form>
                                            @endif
                                            
                                            <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" 
                                                  class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger" title="Xóa">
                                                    🗑
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">
                                        Không có đánh giá nào
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $reviews->withQueryString()->links('vendor.pagination.bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
