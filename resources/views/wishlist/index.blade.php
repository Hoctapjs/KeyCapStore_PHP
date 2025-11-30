@extends('layouts.app')

@section('title', 'Danh sách yêu thích')

@push('styles')
<style>
    .product-item {
        height: 100%;
        display: flex;
        flex-direction: column;
        cursor: pointer;
        position: relative;
    }
    .product-item figure {
        margin-bottom: 1rem;
        overflow: hidden;
    }
    .product-item h3 {
        min-height: 3em;
        margin-bottom: 0.5rem;
    }
    .product-item .price {
        margin-top: auto;
        margin-bottom: 1rem;
    }
    
    /* Wishlist remove button */
    .btn-remove-wishlist {
        width: 38px;
        height: 38px;
        background-color: rgba(255, 68, 68, 0.3);
        border: none;
        padding: 0;
        transition: all 0.3s ease;
    }
    .btn-remove-wishlist:hover {
        background-color: #ff4444;
        transform: scale(1.1);
    }
</style>
@endpush

@section('content')

<section class="py-5 overflow-hidden">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="section-header d-flex flex-wrap justify-content-between mb-5">
                    <h2 class="section-title">Danh sách yêu thích của tôi</h2>
                </div>
            </div>
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <div class="row">
            <!-- Sidebar Filter -->
            <div class="col-md-3">
                <div class="card border-0 mb-4">
                    <div class="card-body">
                        <h5 class="mb-4">Bộ lọc</h5>
                        
                        <form method="GET" action="{{ route('wishlist.index') }}" id="filterForm">
                            <!-- Search -->
                            <div class="mb-4">
                                <label class="form-label">Tìm kiếm</label>
                                <input type="text" name="search" value="{{ request('search') }}" 
                                       class="form-control" placeholder="Tên sản phẩm...">
                            </div>

                            <!-- Categories -->
                            <div class="mb-4">
                                <h6 class="mb-3">Danh mục</h6>
                                @foreach($categories as $category)
                                    <div class="mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="category" 
                                                   value="{{ $category->slug }}" id="cat-{{ $category->id }}"
                                                   {{ request('category') == $category->slug ? 'checked' : '' }}>
                                            <label class="form-check-label" for="cat-{{ $category->id }}">
                                                {{ $category->name }}
                                            </label>
                                        </div>
                                        @if($category->children->count() > 0)
                                            <div class="ms-4 mt-1">
                                                @foreach($category->children as $child)
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="category" 
                                                               value="{{ $child->slug }}" id="cat-{{ $child->id }}"
                                                               {{ request('category') == $child->slug ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="cat-{{ $child->id }}">
                                                            {{ $child->name }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            <!-- Brands -->
                            <div class="mb-4">
                                <h6 class="mb-3">Thương hiệu</h6>
                                @foreach($brands->take(5) as $brand)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="brand" 
                                               value="{{ $brand->slug }}" id="brand-{{ $brand->id }}"
                                               {{ request('brand') == $brand->slug ? 'checked' : '' }}>
                                        <label class="form-check-label" for="brand-{{ $brand->id }}">
                                            {{ $brand->name }}
                                        </label>
                                    </div>
                                @endforeach
                                @if($brands->count() > 5)
                                    <small class="text-muted">+{{ $brands->count() - 5 }} thương hiệu khác</small>
                                @endif
                            </div>

                            <!-- Price Range -->
                            <div class="mb-4">
                                <h6 class="mb-3">Khoảng giá</h6>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <input type="number" name="min_price" value="{{ request('min_price') }}" 
                                               class="form-control form-control-sm" placeholder="Từ">
                                    </div>
                                    <div class="col-6">
                                        <input type="number" name="max_price" value="{{ request('max_price') }}" 
                                               class="form-control form-control-sm" placeholder="Đến">
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 mb-2">Áp dụng</button>
                            <a href="{{ route('wishlist.index') }}" class="btn btn-outline-secondary w-100">Xóa bộ lọc</a>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="col-md-9">
                <!-- Sort & Count -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <p class="text-muted mb-0">Hiển thị {{ $wishlists->count() }} / {{ $wishlists->total() }} sản phẩm</p>
                    <form method="GET" action="{{ route('wishlist.index') }}" class="d-flex align-items-center gap-2">
                        @foreach(request()->except('sort') as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        <label class="mb-0 me-2">Sắp xếp:</label>
                        <select name="sort" onchange="this.form.submit()" class="form-select form-select-sm" style="width: auto;">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Mới nhất</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá tăng dần</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá giảm dần</option>
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Tên A-Z</option>
                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Tên Z-A</option>
                        </select>
                    </form>
                </div>

                <!-- Products -->
                @if($wishlists->count() > 0)
                <div class="product-grid row row-cols-1 row-cols-sm-2 row-cols-lg-3 row-cols-xl-4 g-4">
                    @foreach($wishlists as $wishlist)
                        <div class="col">
                            <div class="product-item" data-url="{{ route('products.show', $wishlist->product->slug) }}">
                                <form action="{{ route('wishlist.remove', $wishlist->product) }}" method="POST" class="position-absolute end-0 m-3" style="z-index: 10;" onsubmit="event.stopPropagation();">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn rounded-circle d-flex align-items-center justify-content-center btn-remove-wishlist" title="Xóa khỏi yêu thích" onclick="event.stopPropagation();">
                                        <svg width="18" height="18" fill="white" viewBox="0 0 16 16">
                                            <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z"/>
                                        </svg>
                                    </button>
                                </form>
                                <figure>
                                        @if($wishlist->product->productImages->first())
                                            <img src="{{ $wishlist->product->productImages->first()->image_url }}" 
                                                 alt="{{ $wishlist->product->title }}" class="tab-image" 
                                                 style="width: 100%; height: 300px; object-fit: cover;">
                                        @else
                                            <img src="{{ asset('images/placeholder.svg') }}" 
                                                 alt="{{ $wishlist->product->title }}" class="tab-image"
                                                 style="width: 100%; height: 300px; object-fit: contain; background: #f8f8f8;">
                                        @endif
                                </figure>
                                <h3>{{ Str::limit($wishlist->product->title, 40) }}</h3>
                                @if($wishlist->product->brand)
                                    <span class="qty">{{ $wishlist->product->brand->name }}</span>
                                @endif
                                <span class="rating">
                                    <svg width="24" height="24" class="text-primary">
                                        <use xlink:href="#star-solid"></use>
                                    </svg> 
                                    {{ $wishlist->product->reviews_avg_rating ? number_format($wishlist->product->reviews_avg_rating, 1) : '0.0' }}
                                </span>
                                <span class="price">{{ number_format($wishlist->product->min_price, 0, ',', '.') }}đ</span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if ($wishlists->hasPages())
                <div class="mt-5">
                    <nav>
                        {{ $wishlists->onEachSide(1)->appends(request()->query())->links('vendor.pagination.custom') }}
                    </nav>
                </div>
                @endif

                @else
                <div class="text-center py-5">
                    <svg width="120" height="120" class="text-muted mb-3">
                        <use xlink:href="#heart"></use>
                    </svg>
                    <h4 class="text-muted mb-3">Không tìm thấy sản phẩm nào</h4>
                    <p class="text-muted mb-4">Thử thay đổi bộ lọc hoặc tìm kiếm khác!</p>
                    <a href="{{ route('wishlist.index') }}" class="btn btn-outline-secondary me-2">Xóa bộ lọc</a>
                    <a href="{{ route('products.index') }}" class="btn btn-primary">
                        Khám phá sản phẩm
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
$(document).ready(function() {
    // Handle product card click
    $('.product-item').click(function() {
        var url = $(this).data('url');
        if (url) {
            window.location.href = url;
        }
    });
});
</script>
@endpush

@endsection
