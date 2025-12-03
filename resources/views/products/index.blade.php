@extends('layouts.app')

@section('title', 'Sản phẩm')

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
    
    /* Wishlist button styles */
    .btn-wishlist {
        z-index: 10;
        width: 40px;
        height: 40px;
        background-color: rgba(255, 255, 255, 0.9);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }
    .btn-wishlist:hover {
        background-color: #fff;
        transform: scale(1.1);
    }
    .btn-wishlist[data-in-wishlist="true"] {
        background-color: #dc3545;
        
    }
 
    .btn-wishlist[data-in-wishlist="true"] svg {
        fill: #dc3545;
        stroke: #dc3545;
    }
    .btn-wishlist[data-in-wishlist="false"] svg {
        fill: none;
        stroke: #333;
    }
    .btn-wishlist[data-in-wishlist="false"]:hover svg {
        fill: #dc3545;
        stroke: #dc3545;
    }
</style>
@endpush

@section('content')

<section class="py-5 overflow-hidden">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="section-header d-flex flex-wrap justify-content-between mb-5">
                    <h2 class="section-title">Sản phẩm</h2>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Sidebar Filter -->
            <div class="col-md-3">
                <div class="card border-0 mb-4">
                    <div class="card-body">
                        <h5 class="mb-4">Bộ lọc</h5>
                        
                        <form method="GET" action="{{ route('products.index') }}" id="filterForm">
                            <!-- Search -->
                            <div class="mb-4">
                                <label class="form-label">Tìm kiếm</label>
                                <input type="text" name="search" value="{{ request('search') }}" 
                                       class="form-control" placeholder="Tên sản phẩm...">
                            </div>

                            <!-- Categories -->
                            <div class="mb-4">
                                <h6 class="mb-3">Danh mục</h6>
                                @if($categories->count() > 5)
                                    <div class="mb-2">
                                        <input type="text" class="form-control form-control-sm" 
                                               id="categorySearch" placeholder="Tìm danh mục...">
                                    </div>
                                @endif
                                <div id="categoryList">
                                    @foreach($categories->take(5) as $category)
                                        <div class="mb-2 category-item" data-name="{{ strtolower($category->name) }}">
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
                                                        <div class="form-check category-child" data-name="{{ strtolower($child->name) }}">
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
                                @if($categories->count() > 5)
                                    <div id="categoryMore" class="mt-2">
                                        <a href="javascript:void(0)" class="text-primary small" onclick="toggleMoreCategories()">
                                            <span id="categoryToggleText">+{{ $categories->count() - 5 }} danh mục khác</span>
                                        </a>
                                    </div>
                                    <div id="categoryHidden" style="display: none;">
                                        @foreach($categories->skip(5) as $category)
                                            <div class="mb-2 category-item" data-name="{{ strtolower($category->name) }}">
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
                                                            <div class="form-check category-child" data-name="{{ strtolower($child->name) }}">
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
                                @endif
                            </div>

                            <!-- Brands -->
                            <div class="mb-4">
                                <h6 class="mb-3">Thương hiệu</h6>
                                @if($brands->count() > 5)
                                    <div class="mb-2">
                                        <input type="text" class="form-control form-control-sm" 
                                               id="brandSearch" placeholder="Tìm thương hiệu...">
                                    </div>
                                @endif
                                <div id="brandList">
                                    @foreach($brands->take(5) as $brand)
                                        <div class="form-check mb-2 brand-item" data-name="{{ strtolower($brand->name) }}">
                                            <input class="form-check-input" type="radio" name="brand" 
                                                   value="{{ $brand->slug }}" id="brand-{{ $brand->id }}"
                                                   {{ request('brand') == $brand->slug ? 'checked' : '' }}>
                                            <label class="form-check-label" for="brand-{{ $brand->id }}">
                                                {{ $brand->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                @if($brands->count() > 5)
                                    <div id="brandMore" class="mt-2">
                                        <a href="javascript:void(0)" class="text-primary small" onclick="toggleMoreBrands()">
                                            <span id="brandToggleText">+{{ $brands->count() - 5 }} thương hiệu khác</span>
                                        </a>
                                    </div>
                                    <div id="brandHidden" style="display: none;">
                                        @foreach($brands->skip(5) as $brand)
                                            <div class="form-check mb-2 brand-item" data-name="{{ strtolower($brand->name) }}">
                                                <input class="form-check-input" type="radio" name="brand" 
                                                       value="{{ $brand->slug }}" id="brand-{{ $brand->id }}"
                                                       {{ request('brand') == $brand->slug ? 'checked' : '' }}>
                                                <label class="form-check-label" for="brand-{{ $brand->id }}">
                                                    {{ $brand->name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
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

                            <!-- Rating Filter -->
                            <div class="mb-4">
                                <h6 class="mb-3">Đánh Giá</h6>
                                @for($i = 5; $i >= 1; $i--)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="rating" 
                                               value="{{ $i }}" id="rating-{{ $i }}"
                                               {{ request('rating') == $i ? 'checked' : '' }}>
                                        <label class="form-check-label d-flex align-items-center" for="rating-{{ $i }}">
                                            <span style="color: #f0ad4e;" class="me-1">
                                                @for($j = 1; $j <= 5; $j++)
                                                    @if($j <= $i)
                                                        ★
                                                    @else
                                                        <span style="color: #ddd;">★</span>
                                                    @endif
                                                @endfor
                                            </span>
                                            @if($i < 5)
                                                <small class="text-muted">trở lên</small>
                                            @endif
                                        </label>
                                    </div>
                                @endfor
                            </div>

                            <button type="submit" class="btn btn-primary w-100 mb-2">Áp dụng</button>
                            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary w-100">Xóa bộ lọc</a>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="col-md-9">
                <!-- Sort & Count -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <p class="text-muted mb-0">Hiển thị {{ $products->count() }} / {{ $products->total() }} sản phẩm</p>
                    <form method="GET" action="{{ route('products.index') }}" class="d-flex align-items-center gap-2">
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
                <div class="product-grid row row-cols-1 row-cols-sm-2 row-cols-lg-3 row-cols-xl-4 g-4">
                    @forelse($products as $product)
                        <div class="col">
                            <div class="product-item" data-url="{{ route('products.show', $product->slug) }}">
                                @if($product->stock <= 0)
                                    <span class="badge bg-danger position-absolute m-3" style="z-index: 5;">Hết hàng</span>
                                @endif
                                @php
                                    $isInWishlist = auth()->check() && auth()->user()->wishlistProducts->contains($product->id);
                                @endphp
                                <a href="javascript:void(0)" 
                                   class="btn-wishlist wishlist-toggle" 
                                   data-product-id="{{ $product->id }}"
                                   data-in-wishlist="{{ $isInWishlist ? 'true' : 'false' }}"
                                   title="{{ $isInWishlist ? 'Xóa khỏi yêu thích' : 'Thêm vào yêu thích' }}"
                                   onclick="event.stopPropagation();">
                                    <svg width="24" height="24">
                                        <use xlink:href="#heart"></use>
                                    </svg>
                                </a>
                                <figure>
                                        @if($product->productImages->first())
                                            <img src="{{ $product->productImages->first()->image_url }}" 
                                                 alt="{{ $product->title }}" class="tab-image" 
                                                 style="width: 100%; height: 300px; object-fit: cover;">
                                        @else
                                            <img src="{{ asset('images/placeholder.svg') }}" 
                                                 alt="{{ $product->title }}" class="tab-image"
                                                 style="width: 100%; height: 300px; object-fit: contain; background: #f8f8f8;">
                                        @endif
                                </figure>
                                <h3>{{ Str::limit($product->title, 40) }}</h3>
                                @if($product->brand)
                                    <span class="qty">{{ $product->brand->name }}</span>
                                @endif
                                <span class="rating">
                                    <svg width="24" height="24" class="text-primary">
                                        <use xlink:href="#star-solid"></use>
                                    </svg> 
                                    {{ $product->reviews_avg_rating ? number_format($product->reviews_avg_rating, 1) : '0.0' }}
                                </span>
                                <span class="price">{{ number_format($product->min_price, 0, ',', '.') }}đ</span>
                                <!-- <div class="d-flex align-items-center justify-content-between">
                                    <div class="input-group product-qty">
                                        <span class="input-group-btn">
                                            <button type="button" class="quantity-left-minus btn btn-danger btn-number" data-type="minus">
                                                <svg width="16" height="16"><use xlink:href="#minus"></use></svg>
                                            </button>
                                        </span>
                                        <input type="text" name="quantity" class="form-control input-number" value="1" min="1">
                                        <span class="input-group-btn">
                                            <button type="button" class="quantity-right-plus btn btn-success btn-number" data-type="plus">
                                                <svg width="16" height="16"><use xlink:href="#plus"></use></svg>
                                            </button>
                                        </span>
                                    </div>
                                    <a href="#" class="nav-link">
                                        <iconify-icon icon="uil:shopping-cart"></iconify-icon>
                                    </a>
                                </div> -->
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="text-center py-5">
                                <p class="text-muted fs-5">Không tìm thấy sản phẩm nào</p>
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if ($products->hasPages())
                <div class="mt-5">
                    <nav>
                        {{ $products->onEachSide(1)->appends(request()->query())->links('vendor.pagination.custom') }}
                    </nav>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
// Toggle more categories
var categoriesExpanded = false;
function toggleMoreCategories() {
    categoriesExpanded = !categoriesExpanded;
    if (categoriesExpanded) {
        $('#categoryHidden').show();
        $('#categoryToggleText').text('Thu gọn');
    } else {
        $('#categoryHidden').hide();
        $('#categoryToggleText').text('+{{ $categories->count() - 5 }} danh mục khác');
    }
}

// Toggle more brands
var brandsExpanded = false;
function toggleMoreBrands() {
    brandsExpanded = !brandsExpanded;
    if (brandsExpanded) {
        $('#brandHidden').show();
        $('#brandToggleText').text('Thu gọn');
    } else {
        $('#brandHidden').hide();
        $('#brandToggleText').text('+{{ $brands->count() - 5 }} thương hiệu khác');
    }
}

$(document).ready(function() {
    // Category search
    $('#categorySearch').on('input', function() {
        var searchText = $(this).val().toLowerCase();
        if (searchText.length > 0) {
            // Show all categories when searching
            $('#categoryHidden').show();
            $('#categoryMore').hide();
            
            // Filter categories
            $('.category-item, .category-child').each(function() {
                var name = $(this).data('name');
                if (name.indexOf(searchText) > -1) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        } else {
            // Reset to original state
            if (!categoriesExpanded) {
                $('#categoryHidden').hide();
            }
            $('#categoryMore').show();
            $('.category-item, .category-child').show();
        }
    });
    
    // Brand search
    $('#brandSearch').on('input', function() {
        var searchText = $(this).val().toLowerCase();
        if (searchText.length > 0) {
            // Show all brands when searching
            $('#brandHidden').show();
            $('#brandMore').hide();
            
            // Filter brands
            $('.brand-item').each(function() {
                var name = $(this).data('name');
                if (name.indexOf(searchText) > -1) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        } else {
            // Reset to original state
            if (!brandsExpanded) {
                $('#brandHidden').hide();
            }
            $('#brandMore').show();
            $('.brand-item').show();
        }
    });

    // Handle product card click
    $('.product-item').click(function() {
        var url = $(this).data('url');
        if (url) {
            window.location.href = url;
        }
    });
    
    // Handle wishlist toggle
    $('.wishlist-toggle').click(function(e) {
        e.preventDefault();
        
        const $btn = $(this);
        const productId = $btn.data('product-id');
        const isInWishlist = $btn.data('in-wishlist') === 'true' || $btn.data('in-wishlist') === true;
        
        $.ajax({
            url: `/wishlist/toggle/${productId}`,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    // Update button state
                    $btn.data('in-wishlist', response.in_wishlist);
                    $btn.attr('data-in-wishlist', response.in_wishlist);
                    
                    // Update tooltip
                    $btn.attr('title', response.in_wishlist ? 'Xóa khỏi yêu thích' : 'Thêm vào yêu thích');
                    
                    // Update wishlist count in header
                    if (response.count > 0) {
                        if ($('.wishlist-count').length === 0) {
                            $('a[title="Danh sách yêu thích"]').append('<span class="wishlist-count position-absolute top-0 start-100 translate-middle badge rounded-pill" style="font-size: 0.65rem; background-color: #dc3545 !important;">' + response.count + '</span>');
                        } else {
                            $('.wishlist-count').text(response.count).show();
                        }
                    } else {
                        $('.wishlist-count').remove();
                    }
                }
            },
            error: function(xhr) {
                if (xhr.status === 401) {
                    alert('Vui lòng đăng nhập để thêm vào danh sách yêu thích');
                    window.location.href = '{{ route("login.form") }}';
                } else {
                    alert('Có lỗi xảy ra, vui lòng thử lại');
                }
            }
        });
    });
});
</script>
@endpush

@endsection
