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

    /* Related products styling */
    .product-item {
        height: 100%;
        display: flex;
        flex-direction: column;
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
</style>
@endpush

@section('content')

<section class="py-5">
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

        <div class="row g-5">
            <!-- Product Images -->
            <div class="col-md-6">
                <div class="product-preview mb-3">
                    <img id="mainProductImage" src="{{ $product->productImages->first()->image_url ?? asset('images/placeholder.png') }}"
                        alt="{{ $product->title }}" class="img-fluid rounded">
                </div>

                @if($product->productImages->count() > 1)
                <div class="product-thumbnail-slider">
                    <div class="row g-2">
                        @foreach($product->productImages as $image)
                        <div class="col-3">
                            <img src="{{ $image->image_url }}" alt="{{ $image->alt }}"
                                class="img-fluid rounded cursor-pointer border hover-shadow"
                                onclick="document.getElementById('mainProductImage').src='{{ $image->image_url }}'"
                                style="cursor: pointer;">
                        </div>
                        @endforeach
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
                            @if($reviewsCount> 0 && $i <= round($avgRating))
                                ★
                                @else
                                ☆
                                @endif
                                @endfor
                                </div>
                                <span class="fw-semibold">{{ $reviewsCount > 0 ? number_format($avgRating, 1) : '0.0' }}</span>
                                <span class="text-muted">({{ $reviewsCount }} đánh giá)</span>
                    </div>

                    <!-- Price -->
                    <div class="mb-4">
                        <span class="display-4 fw-bold text-primary product-price">
                            {{ number_format($product->price, 0, ',', '.') }}đ
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
                    foreach($product->variants as $variant) {
                    $options = json_decode($variant->option_values, true);
                    $size = $options['size'] ?? null;
                    $color = $options['color'] ?? null;

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
                    'stock' => $variant->stock_quantity
                    ];
                    }
                    }
                    @endphp

                    <div class="mb-4">
                        <!-- <input type="hidden" id="selected-variant-id" name="variant_id"> -->

                        <!-- Size Selection -->
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

                        <!-- Color Selection -->
                        <div class="mb-3" id="color-selection" style="display: none;">
                            <h5 class="mb-3">Chọn màu sắc:</h5>
                            <div id="color-options" class="d-flex gap-2 flex-wrap">
                                <!-- Colors will be populated by JavaScript -->
                            </div>
                        </div>

                        <!-- Hidden data for JavaScript -->
                        <script id="variants-data" type="application/json">
                            @json($colorsBySize)
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
            <div class="row mt-5">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-5">
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
            <div class="row mt-5">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-5">
                            <h3 class="mb-4">Đánh giá sản phẩm</h3>
                            @forelse($product->reviews as $review)
                            <div class="border-bottom pb-4 mb-4">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h6 class="mb-1">{{ $review->user->name }}</h6>
                                        <div class="text-warning">
                                            @for($i = 1; $i <= 5; $i++)
                                                {{ $i <= $review->rating ? '★' : '☆' }}
                                                @endfor
                                                </div>
                                        </div>
                                        <span class="text-muted small">{{ $review->created_at->format('d/m/Y') }}</span>
                                    </div>
                                    @if($review->title)
                                    <h6 class="mb-2">{{ $review->title }}</h6>
                                    @endif
                                    <p class="mb-0">{{ $review->content }}</p>
                                </div>
                                @empty
                                <p class="text-muted">Chưa có đánh giá nào</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Related Products -->
                @if($relatedProducts->count() > 0)
                <div class="row mt-5">
                    <div class="col-12">
                        <h3 class="mb-4">Sản phẩm liên quan</h3>
                        <div class="product-grid row row-cols-2 row-cols-md-4 row-cols-lg-5 g-4">
                            @foreach($relatedProducts as $related)
                            <div class="col">
                                <div class="product-item">
                                    <a href="#" class="btn-wishlist">
                                        <svg width="24" height="24">
                                            <use xlink:href="#heart"></use>
                                        </svg>
                                    </a>
                                    <figure>
                                        <a href="{{ route('products.show', $related->slug) }}" title="{{ $related->title }}">
                                            @if($related->productImages->first())
                                            <img src="{{ $related->productImages->first()->image_url }}"
                                                alt="{{ $related->title }}" class="tab-image"
                                                style="width: 100%; height: 250px; object-fit: cover;">
                                            @else
                                            <img src="{{ asset('images/placeholder.png') }}"
                                                alt="{{ $related->title }}" class="tab-image"
                                                style="width: 100%; height: 250px; object-fit: cover;">
                                            @endif
                                        </a>
                                    </figure>
                                    <h3>{{ Str::limit($related->title, 30) }}</h3>
                                    @if($related->brand)
                                    <span class="qty">{{ $related->brand->name }}</span>
                                    @endif
                                    <span class="price">{{ number_format($related->price, 0, ',', '.') }}đ</span>
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
        // Load variants data
        const variantsData = JSON.parse($('#variants-data').text());
        let selectedSize = null;

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
                colorHtml += `
                    <button type="button" class="btn btn-outline-secondary color-option ${disabled}" 
                            data-variant-id="${colorData.variant_id}"
                            data-price="${colorData.price}"
                            data-stock="${colorData.stock}"
                            ${colorData.stock <= 0 ? 'disabled' : ''}>
                        ${colorData.color}
                        <small class="d-block">${new Intl.NumberFormat('vi-VN').format(colorData.price)}đ</small>
                    </button>
                `;
            });

            $('#color-options').html(colorHtml);

            // Reset selection
            $('#selected-variant-id').val('');

            // Re-bind color click event
            $('.color-option').click(function() {
                const stock = $(this).data('stock');

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
                    // Trường hợp muốn allow click variant hết hàng (nếu bạn không disabled button)
                    $('#stock-status').html(`
            <span class="badge bg-danger fs-6 px-3 py-2 fw-bold">
                Hết hàng
            </span>
        `);
                    $('.input-number, .quantity-right-plus, .quantity-left-minus').prop('disabled', true);
                }
            });
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
</script>
@endpush