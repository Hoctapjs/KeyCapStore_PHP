@extends('layouts.app')

@section('title', $product->title)

@push('styles')
<style>
    .cursor-pointer {
        cursor: pointer;
    }

    .cursor-not-allowed {
        cursor: not-allowed;
    }

    /* Star rating */
    .text-warning {
        font-size: 2rem;
        line-height: 1;
        color: #ffc107 !important;
        font-weight: bold;
        text-shadow: 0 0 2px rgba(0, 0, 0, 0.2);
    }

    /* Product images */
    .product-preview {
        margin-bottom: 1rem;
        width: 100%;
        max-width: 600px;
        height: 550px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8f9fa;
        border-radius: 0.5rem;
        overflow: hidden;
    }

    .product-preview img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    @media screen and (min-width: 992px) {
        .product-thumbnail-slider {
            height: 100px;
        }
    }

    .product-thumbnail-slider {
        position: relative;
        overflow: hidden;
        
    }

    .thumbnail-container {
        display: flex;
        gap: 0.5rem;
        transition: transform 0.3s ease;
    }

    .thumbnail-item {
        flex: 0 0 auto;
        width: 100px;
    }

    .thumbnail-item img {
        width: 100%;
        height: 100px;
        object-fit: cover;
        cursor: pointer;
        border: 2px solid transparent;
        transition: all 0.3s ease;
        border-radius: 0.25rem;
    }

    .thumbnail-item img:hover,
    .thumbnail-item img.active {
        border-color: #0d6efd;
        transform: scale(1.05);
    }

    .thumbnail-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(0, 0, 0, 0.5);
        color: white;
        border: none;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        cursor: pointer;
        z-index: 10;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.3s;
    }

    .thumbnail-nav:hover {
        background: rgba(0, 0, 0, 0.8);
    }

    .thumbnail-nav-prev {
        left: 0;
    }

    .thumbnail-nav-next {
        right: 0;
    }

    .thumbnail-nav:disabled {
        opacity: 0.3;
        cursor: not-allowed;
    }

    /* Related products styling */
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
        min-height: 2.5em;
        margin-bottom: 0.5rem;
        font-size: 1rem;
    }

    .product-item .price {
        margin-top: auto;
    }
    
    /* Wishlist button styles */
    .btn-wishlist {
        position: absolute;
        top: 10px;
        right: 10px;
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

<section class="py-4">
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Sản phẩm</a></li>
                @if($product->categories->first())
                <li class="breadcrumb-item"><a href="{{ route('categories.show', $product->categories->first()->slug) }}">{{ $product->categories->first()->name }}</a></li>
                @endif
                <li class="breadcrumb-item active">{{ $product->title }}</li>
            </ol>
        </nav>

        <div class="row g-4">
            <!-- Product Images -->
            <div class="col-md-6">
                <div class="product-preview">
                    <img id="mainProductImage" src="{{ $product->productImages->first()->image_url ?? asset('images/placeholder.svg') }}"
                        alt="{{ $product->title }}">
                </div>

                @if($product->productImages->count() > 1)
                <div class="product-thumbnail-slider mt-3">
                    <button class="thumbnail-nav thumbnail-nav-prev" id="thumbPrev" onclick="scrollThumbnails(-1)">
                        ‹
                    </button>
                    <button class="thumbnail-nav thumbnail-nav-next" id="thumbNext" onclick="scrollThumbnails(1)">
                        ›
                    </button>
                    <div style="overflow: hidden; padding: 0 35px; height: 110px;">
                        <div class="thumbnail-container" id="thumbnailContainer">
                            @foreach($product->productImages as $index => $image)
                            <div class="thumbnail-item">
                                <img src="{{ $image->image_url }}" 
                                     alt="{{ $image->alt }}"
                                     class="rounded {{ $index === 0 ? 'active' : '' }}"
                                     data-index="{{ $index }}"
                                     data-image-url="{{ $image->image_url }}"
                                     onclick="changeMainImage(this.dataset.imageUrl, this.dataset.index)">
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Product Info -->
            <div class="col-md-6">
                <h1 class="display-5 fw-bold mb-3">{{ $product->title }}</h1>

                @if($product->brand)
                <p class="text-muted mb-3">
                    Thương hiệu: <a href="{{ route('products.index', ['brand' => $product->brand->slug]) }}"
                        class="text-decoration-none fw-semibold">{{ $product->brand->name }}</a>
                </p>
                @endif

                <!-- Rating -->
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="text-warning">
                        @for($i = 1; $i <= 5; $i++)
                            @if($totalReviews > 0 && $i <= round($avgRating))
                                ★
                                @else
                                ☆
                                @endif
                                @endfor
                                </div>
                                <span class="fw-semibold">{{ $totalReviews > 0 ? number_format($avgRating, 1) : '0.0' }}</span>
                                <span class="text-muted">({{ $totalReviews }} đánh giá)</span>
                    </div>

                    <!-- Price -->
                    <div class="mb-4">
                        <span class="display-4 fw-bold text-primary product-price">
                            {{ $product->price_range }}
                        </span>
                    </div>

                    <!-- Stock Status -->
                    <!-- <div class="mb-4">
                        @php
                        $displayStock = $product->display_stock;
                        @endphp

                        @if($displayStock > 0)
                        <span class="badge bg-success fs-6 px-3 py-2">
                            Còn {{ $displayStock }} sản phẩm
                        </span>
                        @else
                        <span class="badge bg-danger fs-6 px-3 py-2 fw-bold">Hết hàng</span>
                        @endif
                    </div> -->
                    @php
                    $displayStock = $product->display_stock;
                    @endphp

                    <div class="mb-4" id="stock-status">
                        @if($displayStock > 0)
                        <span class="badge bg-success fs-6 px-3 py-2">
                            Còn {{ $displayStock }} sản phẩm
                        </span>
                        @else
                        <span class="badge bg-danger fs-6 px-3 py-2 fw-bold">
                            Hết hàng
                        </span>
                        @endif
                    </div>

                    <!-- Variants -->
                    @if($product->variants->count() > 0)
                    @php
                    // Group variants by size and color
                    $sizes = [];
                    $colorsBySize = [];
                    $colorsOnly = [];
                    
                    foreach($product->variants as $variant) {
                        $options = is_array($variant->option_values) ? $variant->option_values : [];
                        $size = $options['size'] ?? null;
                        $color = $options['color'] ?? null;
                        
                        // Get first variant image
                        $variantImage = $variant->images->first();

                        if($size && !in_array($size, $sizes)) {
                            $sizes[] = $size;
                        }
                        
                        if($size && $color) {
                            if(!isset($colorsBySize[$size])) {
                                $colorsBySize[$size] = [];
                            }
                            $colorsBySize[$size][] = [
                                'color' => $color,
                                'variant_id' => $variant->id,
                                'price' => $variant->price,
                                'stock' => $variant->stock_quantity,
                                'image' => $variantImage ? $variantImage->image_url : null
                            ];
                        } elseif (!$size && $color) {
                            // Chỉ có màu, không có size
                            $colorsOnly[] = [
                                'color' => $color,
                                'variant_id' => $variant->id,
                                'price' => $variant->price,
                                'stock' => $variant->stock_quantity,
                                'image' => $variantImage ? $variantImage->image_url : null
                            ];
                        }
                    }
                    @endphp

                    <div class="mb-4">
                        <!-- Size Selection (chỉ hiện khi có sizes) -->
                        @if(count($sizes) > 0)
                        <div class="mb-3">
                            <h5 class="mb-3">Chọn kích thước:</h5>
                            <div class="d-flex gap-2 flex-wrap" id="size-options">
                                @foreach($sizes as $size)
                                <button type="button" class="btn btn-outline-primary size-option" data-size="{{ $size }}">
                                    {{ $size }}
                                </button>
                                @endforeach
                            </div>
                        </div>

                        <!-- Color Selection (hiện khi chọn size hoặc khi chỉ có màu) -->
                        <div class="mb-3" id="color-selection" @if(count($colorsOnly) == 0) style="display: none;" @endif>
                            <h5 class="mb-3">Chọn màu sắc:</h5>
                            <div id="color-options" class="d-flex gap-2 flex-wrap">
                                @if(count($colorsOnly) > 0)
                                    @foreach($colorsOnly as $colorData)
                                    <button type="button" class="btn btn-outline-secondary color-option d-flex align-items-center gap-2 {{ $colorData['stock'] <= 0 ? 'disabled opacity-50' : '' }}" 
                                            data-variant-id="{{ $colorData['variant_id'] }}"
                                            data-price="{{ $colorData['price'] }}"
                                            data-stock="{{ $colorData['stock'] }}"
                                            data-image="{{ $colorData['image'] ?? '' }}"
                                            {{ $colorData['stock'] <= 0 ? 'disabled' : '' }}>
                                        @if($colorData['image'])
                                        <img src="{{ $colorData['image'] }}" alt="{{ $colorData['color'] }}" 
                                             style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                                        @endif
                                        <div class="text-start">
                                            <span>{{ $colorData['color'] }}</span>
                                            <small class="d-block">{{ number_format($colorData['price'], 0, ',', '.') }}đ</small>
                                        </div>
                                    </button>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        @else
                        <!-- Chỉ có màu sắc, không có size -->
                        <div class="mb-3">
                            <h5 class="mb-3">Chọn màu sắc:</h5>
                            <div id="color-options" class="d-flex gap-2 flex-wrap">
                                @foreach($colorsOnly as $colorData)
                                <button type="button" class="btn btn-outline-secondary color-option d-flex align-items-center gap-2 {{ $colorData['stock'] <= 0 ? 'disabled opacity-50' : '' }}" 
                                        data-variant-id="{{ $colorData['variant_id'] }}"
                                        data-price="{{ $colorData['price'] }}"
                                        data-stock="{{ $colorData['stock'] }}"
                                        data-image="{{ $colorData['image'] ?? '' }}"
                                        {{ $colorData['stock'] <= 0 ? 'disabled' : '' }}>
                                    @if($colorData['image'])
                                    <img src="{{ $colorData['image'] }}" alt="{{ $colorData['color'] }}" 
                                         style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                                    @endif
                                    <div class="text-start">
                                        <span>{{ $colorData['color'] }}</span>
                                        <small class="d-block">{{ number_format($colorData['price'], 0, ',', '.') }}đ</small>
                                    </div>
                                </button>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Hidden data for JavaScript -->
                        <script id="variants-data" type="application/json">
                            @json($colorsBySize)
                        </script>
                        <script id="colors-only-data" type="application/json">
                            @json($colorsOnly)
                        </script>
                    </div>
                    @endif

                    <!-- Add to Cart -->
                    <!-- <div class="mb-4">
                        <form action="" method="POST">
                            @csrf
                            <div class="row g-3">
                                <div class="col-auto">
                                    <div class="input-group product-qty">
                                        <button type="button" class="quantity-left-minus btn btn-danger btn-number" data-type="minus">
                                            <svg width="16" height="16">
                                                <use xlink:href="#minus"></use>
                                            </svg>
                                        </button>
                                        <input type="text" name="quantity" class="form-control input-number text-center"
                                            value="1" min="1" max="{{ $product->stock }}" style="width: 80px;">
                                        <button type="button" class="quantity-right-plus btn btn-success btn-number" data-type="plus">
                                            <svg width="16" height="16">
                                                <use xlink:href="#plus"></use>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div class="col">
                                    <button type="submit" class="btn btn-primary btn-lg w-100" {{ $product->stock > 0 ? '' : 'disabled' }}>
                                        <iconify-icon icon="uil:shopping-cart" class="me-2"></iconify-icon>
                                        Thêm vào giỏ hàng
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div> -->
                    <form id="addToCartForm" action="javascript:void(0)">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" id="selected-variant-id" name="variant_id">

                        <div class="row g-3">
                            <div class="col-auto">
                                <div class="input-group product-qty">
                                    <button type="button" class="quantity-left-minus btn btn-danger">-</button>
                                    <input type="number" name="quantity" class="form-control text-center input-number"
                                        min="1" value="1" max="{{ $displayStock }}">
                                    <button type="button" class="quantity-right-plus btn btn-success">+</button>
                                </div>
                            </div>

                            <div class="col">
                                <button type="submit" class="btn btn-primary btn-lg w-100">
                                    Thêm vào giỏ hàng
                                </button>
                            </div>
                        </div>
                    </form>

                    @push('scripts')
                    <script>
                        $(function() {
                            $("#addToCartForm").on("submit", function(e) {
                                e.preventDefault();

                                // Nếu sản phẩm có variant thì bắt buộc phải chọn
                                const variantsDataEl = document.getElementById('variants-data');
                                const hasVariants = variantsDataEl && Object.keys(JSON.parse(variantsDataEl.textContent || '{}')).length > 0;

                                const variantId = $('#addToCartForm input[name="variant_id"]').val();

                                if (hasVariants && !variantId) {
                                    alert('Vui lòng chọn kích thước và màu sắc trước khi thêm vào giỏ hàng.');
                                    return;
                                }

                                $.post("{{ route('cart.add') }}", $(this).serialize(), function() {
                                    updateCartUI();
                                });
                            });
                        });
                    </script>
                    @endpush



                    <!-- Categories & Tags -->
                    <div class="border-top pt-4">
                        @if($product->categories->count() > 0)
                        <div class="mb-3">
                            <span class="fw-semibold">Danh mục: </span>
                            @foreach($product->categories as $category)
                            <a href="{{ route('products.index', ['category' => $category->slug]) }}"
                                class="badge bg-light text-dark text-decoration-none">{{ $category->name }}</a>
                            @endforeach
                        </div>
                        @endif
                        @if($product->tags->count() > 0)
                        <div>
                            <span class="fw-semibold">Tags: </span>
                            @foreach($product->tags as $tag)
                            <span class="badge bg-secondary">{{ $tag->name }}</span>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Product Description & Specs -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h3 class="mb-4">Mô tả sản phẩm</h3>
                            <div class="product-description">
                                {!! nl2br(e($product->description)) !!}
                            </div>

                            @if($product->specs)
                            <h4 class="mt-5 mb-4">Thông số kỹ thuật</h4>
                            <table class="table table-bordered">
                                @foreach(json_decode($product->specs, true) as $key => $value)
                                <tr>
                                    <td class="fw-semibold" style="width: 30%">{{ ucfirst($key) }}</td>
                                    <td>{{ $value }}</td>
                                </tr>
                                @endforeach
                            </table>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reviews -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h3 class="mb-0">Đánh giá sản phẩm</h3>
                                @auth
                                    @if($userReview)
                                        <a href="{{ route('review.edit', ['product' => $product->id, 'review' => $userReview->id]) }}" class="btn btn-warning">
                                            <i class="bi bi-pencil-square me-2"></i>Chỉnh sửa đánh giá của bạn
                                        </a>
                                    @else
                                        <a href="{{ route('review.create', $product->id) }}" class="btn btn-primary">
                                            <i class="bi bi-pencil-square me-2"></i>Viết đánh giá
                                        </a>
                                    @endif
                                @else
                                    <a href="{{ route('login.form') }}" class="btn btn-outline-primary">
                                        <i class="bi bi-pencil-square me-2"></i>Đăng nhập để đánh giá
                                    </a>
                                @endauth
                            </div>

                            <!-- Rating Summary + Filter -->
                            <div class="row mb-4">
                                <div class="col-lg-4 text-center text-lg-start mb-4 mb-lg-0">
                                    <div class="bg-light rounded-3 p-4">
                                            <!-- Avg Rating -->
                                        <div class="d-flex align-items-baseline justify-content-center justify-content-lg-start mb-2">
                                            <span class="display-3 fw-bold text-warning me-2">★ {{ number_format($avgRating, 1) }}</span>
                                            <span class="fs-5 text-muted">/ 5</span>
                                        </div>

                                        <!-- Stars -->
                                        <div class="text-warning fs-3 mb-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="bi {{ $i <= round($avgRating) ? 'bi-star-fill' : 'bi-star' }}"></i>
                                            @endfor
                                        </div>

                                        <!-- Total reviews -->
                                        <div class="text-muted">{{ $totalReviews }} đánh giá</div>
                                    </div>
                                </div>

                                <div class="col-lg-8">
                                    <div class="d-flex flex-column gap-2">
                                        @for($i = 5; $i >= 1; $i--)
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="text-warning" style="width: 100px;">
                                                    {{ $i }} <i class="bi bi-star-fill"></i>
                                                </div>
                                                <div class="progress flex-grow-1" style="height: 10px;">
                                                    @php
                                                        $barWidth = $totalReviews > 0
                                                            ? round(($ratingStats[$i] / $totalReviews) * 100, 2)
                                                            : 0;
                                                    @endphp
                                                    <div class="progress-bar" style="width: {{ $barWidth }}%; background-color: #f0ad4e;"></div>
                                                </div>
                                                <div class="text-muted text-end" style="width: 50px;">{{ $ratingStats[$i] }}</div>
                                            </div>
                                        @endfor
                                    </div>

                                    <!-- Filter by star -->
                                    <div class="mt-4 d-flex flex-wrap gap-2">
                                        <a href="{{ route('products.show', $product->slug) }}"
                                        class="btn {{ !request('rating') || request('rating') == 'all' ? 'btn-warning' : 'btn-outline-warning' }} btn-sm px-3">
                                            Tất cả ({{ $totalReviews }})
                                        </a>
                                        @for($i = 5; $i >= 1; $i--)
                                            <a href="{{ route('products.show', $product->slug) . '?rating=' . $i }}"
                                            class="btn {{ request('rating') == $i ? 'btn-warning' : 'btn-outline-warning' }} btn-sm px-3">
                                                {{ $i }} Star ({{ $ratingStats[$i] }})
                                            </a>
                                        @endfor
                                    </div>
                                </div>
                            </div>

                            <!-- Reviews List with Pagination -->
                            <div class="reviews-list">
                                @forelse($reviews as $review)
                                    <div class="border-bottom pb-4 mb-4">
                                        <div class="d-flex justify-content-between mb-2">
                                            <div>
                                                <strong>{{ $review->user->name }}</strong>
                                                <div class="text-warning d-inline-block ms-2">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        {{ $i <= $review->rating ? '★' : '☆' }}
                                                    @endfor
                                                </div>
                                            </div>
                                            <small class="text-muted">{{ $review->created_at->format('d/m/Y') }}</small>
                                        </div>
                                        @if($review->title)
                                            <h6 class="fw-bold text-primary mb-1">{{ $review->title }}</h6>
                                        @endif
                                        <p class="mb-0 text-dark">{{ $review->content }}</p>
                                    </div>
                                @empty
                                    <div class="text-center py-5 text-muted">
                                        <i class="bi bi-chat-square-text fs-1 d-block mb-3"></i>
                                        <p class="fs-5">Chưa có đánh giá nào cho sản phẩm này.</p>
                                    </div>
                                @endforelse
                            </div>

                            <!-- Pagination - chỉ hiện nếu có nhiều hơn 1 trang -->
                            @if($reviews->hasPages())
                                <div class="mt-4">
                                    {{ $reviews->withQueryString()->links('vendor.pagination.bootstrap-5') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

                <!-- Related Products -->
                @if($relatedProducts->count() > 0)
                <div class="row mt-4">
                    <div class="col-12">
                        <h3 class="mb-4">Sản phẩm liên quan</h3>
                        <div class="product-grid row row-cols-2 row-cols-md-4 row-cols-lg-5 g-4">
                            @foreach($relatedProducts as $related)
                            <div class="col">
                                <div class="product-item" data-url="{{ route('products.show', $related->slug) }}">
                                    @php
                                        $isInWishlist = auth()->check() && auth()->user()->wishlistProducts->contains($related->id);
                                    @endphp
                                    <a href="javascript:void(0)" 
                                       class="btn-wishlist wishlist-toggle" 
                                       data-product-id="{{ $related->id }}"
                                       data-in-wishlist="{{ $isInWishlist ? 'true' : 'false' }}"
                                       title="{{ $isInWishlist ? 'Xóa khỏi yêu thích' : 'Thêm vào yêu thích' }}">
                                        <svg width="24" height="24">
                                            <use xlink:href="#heart"></use>
                                        </svg>
                                    </a>
                                    <figure>
                                            @if($related->productImages->first())
                                            <img src="{{ $related->productImages->first()->image_url }}"
                                                 alt="{{ $related->title }}" class="tab-image"
                                                 style="width: 100%; height: 200px; object-fit: cover;">
                                            @else
                                            <img src="{{ asset('images/placeholder.svg') }}"
                                                 alt="{{ $related->title }}" class="tab-image"
                                                 style="width: 100%; height: 200px; object-fit: contain; background: #f8f8f8;">
                                            @endif
                                    </figure>
                                    <h3>{{ Str::limit($related->title, 30) }}</h3>
                                    @if($related->brand)
                                    <span class="qty">{{ $related->brand->name }}</span>
                                    @endif
                                    <span class="price">{{ $related->price_range }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>
</section>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        console.log('jQuery loaded:', typeof jQuery !== 'undefined');
        console.log('Wishlist buttons found:', $('.wishlist-toggle').length);
        
        // Wishlist toggle functionality - MUST be before product-item click
        $(document).on('click', '.wishlist-toggle', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const $btn = $(this);
            const productId = $btn.data('product-id');
            const isInWishlist = $btn.data('in-wishlist') === 'true' || $btn.data('in-wishlist') === true;
            
            console.log('jQuery Wishlist toggle clicked:', productId, 'Current state:', isInWishlist);
            
            @guest
                alert('Vui lòng đăng nhập để thêm vào danh sách yêu thích');
                window.location.href = '{{ route("login.form") }}';
                return;
            @endguest
            
            $.ajax({
                url: `/wishlist/toggle/${productId}`,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    console.log('Wishlist response:', response);
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
                    console.error('Wishlist error:', xhr);
                    if (xhr.status === 401) {
                        alert('Vui lòng đăng nhập để thêm vào danh sách yêu thích');
                        window.location.href = '{{ route("login.form") }}';
                    } else {
                        alert('Có lỗi xảy ra, vui lòng thử lại');
                    }
                }
            });
        });
        
        // Handle product card click for related products (exclude wishlist button)
        $('.product-item').on('click', function(e) {
            // Don't navigate if clicking on wishlist button
            if ($(e.target).closest('.wishlist-toggle').length) {
                return;
            }
            var url = $(this).data('url');
            if (url) {
                window.location.href = url;
            }
        });
        
        // Load variants data
        const variantsData = JSON.parse($('#variants-data').text() || '{}');
        const colorsOnlyData = JSON.parse($('#colors-only-data').text() || '[]');
        let selectedSize = null;

        // Function to bind color click
        function bindColorClick() {
            $('.color-option').click(function() {
                const stock = $(this).data('stock');
                const variantImage = $(this).data('image');

                if (stock > 0) {
                    // Update UI chọn màu
                    $('.color-option').removeClass('btn-secondary').addClass('btn-outline-secondary');
                    $(this).removeClass('btn-outline-secondary').addClass('btn-secondary');

                    // Cập nhật variant_id trong form
                    $('#addToCartForm input[name="variant_id"]').val($(this).data('variant-id'));

                    // Cập nhật giá
                    const price = $(this).data('price');
                    const formattedPrice = new Intl.NumberFormat('vi-VN').format(price);
                    $('.product-price').html(formattedPrice + 'đ');

                    // Đổi ảnh chính nếu biến thể có ảnh
                    if (variantImage) {
                        $('#mainProductImage').attr('src', variantImage);
                    }

                    // Cập nhật max quantity
                    $('.input-number').attr('max', stock);
                    if (parseInt($('.input-number').val()) > stock) {
                        $('.input-number').val(stock);
                    }

                    // Cập nhật badge tồn kho
                    $('#stock-status').html(`
                        <span class="badge bg-success fs-6 px-3 py-2">
                            Còn ${stock} sản phẩm
                        </span>
                    `);

                    // Đảm bảo input + nút +/- dùng được
                    $('.input-number, .quantity-right-plus, .quantity-left-minus').prop('disabled', false);
                } else {
                    $('#stock-status').html(`
                        <span class="badge bg-danger fs-6 px-3 py-2 fw-bold">
                            Hết hàng
                        </span>
                    `);
                    $('.input-number, .quantity-right-plus, .quantity-left-minus').prop('disabled', true);
                }
            });
        }

        // Nếu chỉ có màu sắc (không có size), bind click cho color options ngay
        if (colorsOnlyData.length > 0 && Object.keys(variantsData).length === 0) {
            bindColorClick();
        }

        // Size selection
        $('.size-option').click(function() {
            selectedSize = $(this).data('size');

            // Update UI
            $('.size-option').removeClass('btn-primary').addClass('btn-outline-primary');
            $(this).removeClass('btn-outline-primary').addClass('btn-primary');

            // Show color selection
            $('#color-selection').show();

            // Populate colors for selected size
            const colors = variantsData[selectedSize] || [];
            let colorHtml = '';

            colors.forEach(function(colorData) {
                const disabled = colorData.stock <= 0 ? 'disabled opacity-50' : '';
                const imageHtml = colorData.image 
                    ? `<img src="${colorData.image}" alt="${colorData.color}" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">`
                    : '';
                colorHtml += `
                    <button type="button" class="btn btn-outline-secondary color-option d-flex align-items-center gap-2 ${disabled}" 
                            data-variant-id="${colorData.variant_id}"
                            data-price="${colorData.price}"
                            data-stock="${colorData.stock}"
                            data-image="${colorData.image || ''}"
                            ${colorData.stock <= 0 ? 'disabled' : ''}>
                        ${imageHtml}
                        <div class="text-start">
                            <span>${colorData.color}</span>
                            <small class="d-block">${new Intl.NumberFormat('vi-VN').format(colorData.price)}đ</small>
                        </div>
                    </button>
                `;
            });

            $('#color-options').html(colorHtml);

            // Reset selection
            $('#selected-variant-id').val('');

            // Re-bind color click event
            bindColorClick();
        });

        // Quantity buttons
        $('.quantity-right-plus').click(function(e) {
            e.preventDefault();
            var quantity = parseInt($('.input-number').val());
            var max = parseInt($('.input-number').attr('max'));
            if (quantity < max) {
                $('.input-number').val(quantity + 1);
            }
        });

        $('.quantity-left-minus').click(function(e) {
            e.preventDefault();
            var quantity = parseInt($('.input-number').val());
            if (quantity > 1) {
                $('.input-number').val(quantity - 1);
            }
        });
    });

    // Thumbnail slider functions
    let currentScrollPosition = 0;
    const scrollAmount = 110; // thumbnail width + gap

    function changeMainImage(imageUrl, index) {
        document.getElementById('mainProductImage').src = imageUrl;
        
        // Update active state
        document.querySelectorAll('.thumbnail-item img').forEach(img => {
            img.classList.remove('active');
        });
        document.querySelector(`.thumbnail-item img[data-index="${index}"]`).classList.add('active');
    }

    function scrollThumbnails(direction) {
        const container = document.getElementById('thumbnailContainer');
        const maxScroll = container.scrollWidth - container.parentElement.offsetWidth;
        
        currentScrollPosition += direction * scrollAmount;
        
        // Limit scroll position
        if (currentScrollPosition < 0) currentScrollPosition = 0;
        if (currentScrollPosition > maxScroll) currentScrollPosition = maxScroll;
        
        container.style.transform = `translateX(-${currentScrollPosition}px)`;
        
        // Update button states
        updateNavButtons(maxScroll);
    }

    function updateNavButtons(maxScroll) {
        const prevBtn = document.getElementById('thumbPrev');
        const nextBtn = document.getElementById('thumbNext');
        
        if (prevBtn && nextBtn) {
            prevBtn.disabled = currentScrollPosition <= 0;
            nextBtn.disabled = currentScrollPosition >= maxScroll;
        }
    }

    // Initialize on load
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('thumbnailContainer');
        if (container) {
            const maxScroll = container.scrollWidth - container.parentElement.offsetWidth;
            updateNavButtons(maxScroll);
        }
    });
</script>
@endpush