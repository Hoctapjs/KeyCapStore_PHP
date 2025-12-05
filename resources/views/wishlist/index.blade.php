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
        border-radius: 8px;
    }
    .product-item figure img {
        width: 100%;
        height: 250px;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    .product-item:hover figure img {
        transform: scale(1.05);
    }
    .product-item h3 {
        min-height: 2.5em;
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
        line-height: 1.4;
    }
    .product-item .price {
        margin-top: auto;
        margin-bottom: 1rem;
        font-weight: 600;
        color: #333;
    }
    
    /* Wishlist remove button */
    .btn-remove-wishlist {
        width: 40px;
        height: 40px;
        background-color: rgba(220, 53, 69, 0.9);
        border: none;
        padding: 0;
        transition: all 0.3s ease;
        -webkit-tap-highlight-color: transparent;
        touch-action: manipulation;
    }
    .btn-remove-wishlist:hover {
        background-color: #dc3545;
        transform: scale(1.1);
    }
    .btn-remove-wishlist:focus,
    .btn-remove-wishlist:active {
        outline: none;
        box-shadow: none;
    }

    /* Custom radio button styles */
    .form-check-input[type="radio"] {
        width: 18px;
        height: 18px;
        margin-top: 0;
        vertical-align: middle;
        cursor: pointer;
    }
    .form-check-label {
        cursor: pointer;
        vertical-align: middle;
        line-height: 18px;
        font-size: 0.9rem;
    }
    .form-check {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Mobile Filter Toggle */
    .filter-toggle-btn {
        display: none;
        width: 100%;
        padding: 12px;
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        font-weight: 500;
        margin-bottom: 1rem;
    }

    /* Responsive Styles */
    @media (max-width: 991.98px) {
        .filter-sidebar {
            position: fixed;
            top: 0;
            left: -100%;
            width: 85%;
            max-width: 320px;
            height: 100vh;
            background: #fff;
            z-index: 10100;
            transition: left 0.3s ease;
            overflow-y: auto;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }

        .filter-sidebar.show {
            left: 0;
        }

        .filter-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 10090;
        }

        .filter-overlay.show {
            display: block;
        }

        .filter-toggle-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .filter-close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            z-index: 10;
        }

        .filter-sidebar .card-body {
            padding-top: 50px;
        }

        /* Product grid on mobile */
        .product-item figure img {
            height: 180px;
        }

        .product-item h3 {
            font-size: 0.85rem;
            min-height: 2.2em;
        }

        .product-item .price {
            font-size: 0.9rem;
        }

        /* Sort section mobile */
        .sort-section {
            flex-direction: column;
            gap: 10px;
            align-items: flex-start !important;
        }

        .sort-section .form-select {
            width: 100% !important;
        }
    }

    @media (max-width: 575.98px) {
        .product-item figure img {
            height: 150px;
        }

        .product-item h3 {
            font-size: 0.8rem;
        }

        .btn-remove-wishlist {
            width: 36px;
            height: 36px;
        }
        
        .btn-remove-wishlist svg {
            width: 14px;
            height: 14px;
        }
    }
    
    /* Fix mobile touch highlight */
    .product-item,
    .product-item *,
    .btn-remove-wishlist,
    .btn-remove-wishlist * {
        -webkit-tap-highlight-color: transparent !important;
        -webkit-touch-callout: none !important;
        outline: none !important;
    }
    
    .product-item:focus,
    .product-item:active,
    .product-item:focus-within {
        outline: none !important;
        box-shadow: none !important;
    }
</style>
@endpush

@section('content')

<!-- Filter Overlay for Mobile -->
<div class="filter-overlay" id="filterOverlay" onclick="closeFilter()"></div>

<section class="py-4 overflow-hidden">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="section-header d-flex flex-wrap justify-content-between mb-4">
                    <h2 class="section-title">Danh sách yêu thích</h2>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Mobile Filter Toggle Button -->
            <div class="col-12 d-lg-none">
                <button class="filter-toggle-btn" onclick="openFilter()">
                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5v-2z"/>
                    </svg>
                    Bộ lọc sản phẩm
                </button>
            </div>

            <!-- Sidebar Filter -->
            <div class="col-lg-3 filter-sidebar" id="filterSidebar">
                <button class="filter-close-btn d-lg-none" onclick="closeFilter()">×</button>
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

                            <button type="submit" class="btn btn-primary w-100 mb-2" onclick="closeFilter()">Áp dụng</button>
                            <a href="{{ route('wishlist.index') }}" class="btn btn-outline-secondary w-100">Xóa bộ lọc</a>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="col-lg-9">
                <!-- Sort & Count -->
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2 sort-section">
                    <p class="text-muted mb-0">Hiển thị {{ $wishlists->count() }} / {{ $wishlists->total() }} sản phẩm</p>
                    <form method="GET" action="{{ route('wishlist.index') }}" class="d-flex align-items-center gap-2">
                        @foreach(request()->except('sort') as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        <label class="mb-0 me-2 d-none d-sm-inline">Sắp xếp:</label>
                        <select name="sort" onchange="this.form.submit()" class="form-select form-select-sm">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Mới nhất</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá tăng dần</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá giảm dần</option>
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Tên A-Z</option>
                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Tên Z-A</option>
                        </select>
                    </form>
                </div>

                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <!-- Products -->
                @if($wishlists->count() > 0)
                <div class="product-grid row row-cols-2 row-cols-md-3 row-cols-lg-3 row-cols-xl-4 g-3 g-md-4">
                    @foreach($wishlists as $wishlist)
                        <div class="col">
                            <div class="product-item" data-url="{{ route('products.show', $wishlist->product->slug) }}">
                                <form action="{{ route('wishlist.remove', $wishlist->product) }}" method="POST" class="position-absolute end-0 m-2" style="z-index: 10;" onsubmit="event.stopPropagation();">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn rounded-circle d-flex align-items-center justify-content-center btn-remove-wishlist" title="Xóa khỏi yêu thích" onclick="event.stopPropagation();">
                                        <svg width="16" height="16" fill="white" viewBox="0 0 16 16">
                                            <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z"/>
                                        </svg>
                                    </button>
                                </form>
                                <figure>
                                    @if($wishlist->product->productImages->first())
                                        <img src="{{ $wishlist->product->productImages->first()->image_url }}" 
                                             alt="{{ $wishlist->product->title }}" class="tab-image">
                                    @else
                                        <img src="{{ asset('images/placeholder.svg') }}" 
                                             alt="{{ $wishlist->product->title }}" class="tab-image"
                                             style="background: #f8f8f8;">
                                    @endif
                                </figure>
                                <h3>{{ Str::limit($wishlist->product->title, 35) }}</h3>
                                @if($wishlist->product->brand)
                                    <span class="qty" style="font-size: 0.8rem;">{{ $wishlist->product->brand->name }}</span>
                                @endif
                                <span class="rating" style="font-size: 0.85rem;">
                                    <svg width="18" height="18" class="text-primary">
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
                    <svg width="80" height="80" class="text-muted mb-3">
                        <use xlink:href="#heart"></use>
                    </svg>
                    <h4 class="text-muted mb-3">Không tìm thấy sản phẩm nào</h4>
                    <p class="text-muted mb-4">Thử thay đổi bộ lọc hoặc tìm kiếm khác!</p>
                    <div class="d-flex flex-column flex-sm-row justify-content-center gap-2">
                        <a href="{{ route('wishlist.index') }}" class="btn btn-outline-secondary">Xóa bộ lọc</a>
                        <a href="{{ route('products.index') }}" class="btn btn-primary">
                            Khám phá sản phẩm
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
// Mobile Filter Toggle Functions
function openFilter() {
    document.getElementById('filterSidebar').classList.add('show');
    document.getElementById('filterOverlay').classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeFilter() {
    document.getElementById('filterSidebar').classList.remove('show');
    document.getElementById('filterOverlay').classList.remove('show');
    document.body.style.overflow = '';
}

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

    // Toggle radio buttons (click again to uncheck)
    $('input[type="radio"][name="category"], input[type="radio"][name="brand"]').each(function() {
        var $radio = $(this);
        $radio.data('waschecked', $radio.is(':checked'));
    });
    
    $('input[type="radio"][name="category"], input[type="radio"][name="brand"]').click(function() {
        var $radio = $(this);
        if ($radio.data('waschecked') === true) {
            $radio.prop('checked', false);
            $radio.data('waschecked', false);
        } else {
            $('input[name="' + $radio.attr('name') + '"]').data('waschecked', false);
            $radio.data('waschecked', true);
        }
    });

    // Handle product card click - but not on remove button
    $('.product-item').on('click', function(e) {
        if ($(e.target).closest('.btn-remove-wishlist, form').length > 0) {
            return;
        }
        var url = $(this).data('url');
        if (url) {
            window.location.href = url;
        }
    });
    
    // Force remove focus on mobile
    $('.btn-remove-wishlist').on('click', function() {
        var $btn = $(this);
        setTimeout(function() {
            $btn.blur();
            if (document.activeElement) {
                document.activeElement.blur();
            }
        }, 10);
    });
});
</script>
@endpush

@endsection
