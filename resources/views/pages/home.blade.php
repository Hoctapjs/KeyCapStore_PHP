@extends('layouts.app')

@section('title', 'Trang chủ')

@push('styles')
{{-- nếu trang này cần CSS riêng thì thêm ở đây --}}
<style>
    .temp-header {
        color: #007bff;
    }

    .product-item {
        height: 100%;
        display: flex;
        flex-direction: column;
        border: 1px solid #f0f0f0;
        /* Thêm viền nhẹ nếu cần */
        transition: all 0.3s ease;
    }

    .product-item:hover {
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .product-item figure {
        margin-bottom: 1rem;
        overflow: hidden;
        position: relative;
    }

    .product-item h3 {
        min-height: 3em;
        /* Giữ chiều cao đều nhau cho tên sản phẩm */
        margin-bottom: 0.5rem;
        font-size: 1.1rem;
    }

    .product-item .price {
        margin-top: auto;
        margin-bottom: 1rem;
        font-weight: bold;
        color: #007bff;
        font-size: 1.2rem;
    }

    /* CSS cho ảnh để đảm bảo không bị méo */
    .tab-image {
        width: 100%;
        height: 250px;
        object-fit: cover;
    }
</style>
@endpush

@section('content')

{{-- nội dung trang ở đây --}}

{{-- Lúc nào cũng phải bắt đầu bằng section -> container-fluid trước nhé --}}
<section class="py-5">
    <div class="container-fluid">
        {{-- ở đây muốn bao nhiêu row thì chỉnh, mỗi section có thể có nhiều row (hàng), mỗi row có thể có nhiều col (cột) --}}

        {{-- Row 1 - hàng 1 có 1 cột --}}
        <div class="row">
            <div class="col-md-12">
                <h1 class="mb-4 temp-header">Home</h1>

                <div class="bootstrap-tabs product-tabs">
                    <div class="tabs-header d-flex justify-content-between border-bottom my-5">
                        <h3>Trending Products</h3>
                        <nav>
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <a href="#" class="nav-link text-uppercase fs-6 active" id="nav-all-tab" data-bs-toggle="tab" data-bs-target="#nav-all">All</a>
                                <a href="#" class="nav-link text-uppercase fs-6" id="nav-fruits-tab" data-bs-toggle="tab" data-bs-target="#nav-fruits">Fruits & Veges</a>
                                <a href="#" class="nav-link text-uppercase fs-6" id="nav-juices-tab" data-bs-toggle="tab" data-bs-target="#nav-juices">Juices</a>
                            </div>
                        </nav>
                    </div>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-all" role="tabpanel" aria-labelledby="nav-all-tab">

                            <div class="product-grid row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5">

                                <div class="col">
                                    <div class="product-item">
                                        <span class="badge bg-success position-absolute m-3">-30%</span>
                                        <a href="#" class="btn-wishlist"><svg width="24" height="24">
                                                <use xlink:href="#heart"></use>
                                            </svg></a>
                                        <figure>
                                            <a href="index.html" title="Product Title">
                                                <img src="{{ asset('images/thumb-bananas.png') }}" class="tab-image">
                                            </a>
                                        </figure>
                                        <h3>Sunstar Fresh Melon Juice</h3>
                                        <span class="qty">1 Unit</span><span class="rating"><svg width="24" height="24" class="text-primary">
                                                <use xlink:href="#star-solid"></use>
                                            </svg> 4.5</span>
                                        <span class="price">$18.00</span>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="input-group product-qty">
                                                <span class="input-group-btn">
                                                    <button type="button" class="quantity-left-minus btn btn-danger btn-number" data-type="minus">
                                                        <svg width="16" height="16">
                                                            <use xlink:href="#minus"></use>
                                                        </svg>
                                                    </button>
                                                </span>
                                                <input type="text" id="quantity" name="quantity" class="form-control input-number" value="1">
                                                <span class="input-group-btn">
                                                    <button type="button" class="quantity-right-plus btn btn-success btn-number" data-type="plus">
                                                        <svg width="16" height="16">
                                                            <use xlink:href="#plus"></use>
                                                        </svg>
                                                    </button>
                                                </span>
                                            </div>
                                            <a href="#" class="nav-link">Add to Cart <iconify-icon icon="uil:shopping-cart"></a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="product-item">
                                        <span class="badge bg-success position-absolute m-3">-30%</span>
                                        <a href="#" class="btn-wishlist"><svg width="24" height="24">
                                                <use xlink:href="#heart"></use>
                                            </svg></a>
                                        <figure>
                                            <a href="index.html" title="Product Title">
                                                <img src="{{ asset('images/thumb-biscuits.png') }}" class="tab-image">
                                            </a>
                                        </figure>
                                        <h3>Sunstar Fresh Melon Juice</h3>
                                        <span class="qty">1 Unit</span><span class="rating"><svg width="24" height="24" class="text-primary">
                                                <use xlink:href="#star-solid"></use>
                                            </svg> 4.5</span>
                                        <span class="price">$18.00</span>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="input-group product-qty">
                                                <span class="input-group-btn">
                                                    <button type="button" class="quantity-left-minus btn btn-danger btn-number" data-type="minus">
                                                        <svg width="16" height="16">
                                                            <use xlink:href="#minus"></use>
                                                        </svg>
                                                    </button>
                                                </span>
                                                <input type="text" id="quantity" name="quantity" class="form-control input-number" value="1">
                                                <span class="input-group-btn">
                                                    <button type="button" class="quantity-right-plus btn btn-success btn-number" data-type="plus">
                                                        <svg width="16" height="16">
                                                            <use xlink:href="#plus"></use>
                                                        </svg>
                                                    </button>
                                                </span>
                                            </div>
                                            <a href="#" class="nav-link">Add to Cart <iconify-icon icon="uil:shopping-cart"></a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="product-item">
                                        <a href="#" class="btn-wishlist"><svg width="24" height="24">
                                                <use xlink:href="#heart"></use>
                                            </svg></a>
                                        <figure>
                                            <a href="index.html" title="Product Title">
                                                <img src="{{ asset('images/thumb-cucumber.png') }}" class="tab-image">
                                            </a>
                                        </figure>
                                        <h3>Sunstar Fresh Melon Juice</h3>
                                        <span class="qty">1 Unit</span><span class="rating"><svg width="24" height="24" class="text-primary">
                                                <use xlink:href="#star-solid"></use>
                                            </svg> 4.5</span>
                                        <span class="price">$18.00</span>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="input-group product-qty">
                                                <span class="input-group-btn">
                                                    <button type="button" class="quantity-left-minus btn btn-danger btn-number" data-type="minus">
                                                        <svg width="16" height="16">
                                                            <use xlink:href="#minus"></use>
                                                        </svg>
                                                    </button>
                                                </span>
                                                <input type="text" id="quantity" name="quantity" class="form-control input-number" value="1">
                                                <span class="input-group-btn">
                                                    <button type="button" class="quantity-right-plus btn btn-success btn-number" data-type="plus">
                                                        <svg width="16" height="16">
                                                            <use xlink:href="#plus"></use>
                                                        </svg>
                                                    </button>
                                                </span>
                                            </div>
                                            <a href="#" class="nav-link">Add to Cart <iconify-icon icon="uil:shopping-cart"></a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="product-item">
                                        <a href="#" class="btn-wishlist"><svg width="24" height="24">
                                                <use xlink:href="#heart"></use>
                                            </svg></a>
                                        <figure>
                                            <a href="index.html" title="Product Title">
                                                <img src="{{ asset('images/thumb-milk.png') }}" class="tab-image">
                                            </a>
                                        </figure>
                                        <h3>Sunstar Fresh Melon Juice</h3>
                                        <span class="qty">1 Unit</span><span class="rating"><svg width="24" height="24" class="text-primary">
                                                <use xlink:href="#star-solid"></use>
                                            </svg> 4.5</span>
                                        <span class="price">$18.00</span>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="input-group product-qty">
                                                <span class="input-group-btn">
                                                    <button type="button" class="quantity-left-minus btn btn-danger btn-number" data-type="minus">
                                                        <svg width="16" height="16">
                                                            <use xlink:href="#minus"></use>
                                                        </svg>
                                                    </button>
                                                </span>
                                                <input type="text" id="quantity" name="quantity" class="form-control input-number" value="1">
                                                <span class="input-group-btn">
                                                    <button type="button" class="quantity-right-plus btn btn-success btn-number" data-type="plus">
                                                        <svg width="16" height="16">
                                                            <use xlink:href="#plus"></use>
                                                        </svg>
                                                    </button>
                                                </span>
                                            </div>
                                            <a href="#" class="nav-link">Add to Cart <iconify-icon icon="uil:shopping-cart"></a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="product-item">
                                        <a href="#" class="btn-wishlist"><svg width="24" height="24">
                                                <use xlink:href="#heart"></use>
                                            </svg></a>
                                        <figure>
                                            <a href="index.html" title="Product Title">
                                                <img src="{{ asset('images/thumb-bananas.png') }}" class="tab-image">
                                            </a>
                                        </figure>
                                        <h3>Sunstar Fresh Melon Juice</h3>
                                        <span class="qty">1 Unit</span><span class="rating"><svg width="24" height="24" class="text-primary">
                                                <use xlink:href="#star-solid"></use>
                                            </svg> 4.5</span>
                                        <span class="price">$18.00</span>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="input-group product-qty">
                                                <span class="input-group-btn">
                                                    <button type="button" class="quantity-left-minus btn btn-danger btn-number" data-type="minus">
                                                        <svg width="16" height="16">
                                                            <use xlink:href="#minus"></use>
                                                        </svg>
                                                    </button>
                                                </span>
                                                <input type="text" id="quantity" name="quantity" class="form-control input-number" value="1">
                                                <span class="input-group-btn">
                                                    <button type="button" class="quantity-right-plus btn btn-success btn-number" data-type="plus">
                                                        <svg width="16" height="16">
                                                            <use xlink:href="#plus"></use>
                                                        </svg>
                                                    </button>
                                                </span>
                                            </div>
                                            <a href="#" class="nav-link">Add to Cart <iconify-icon icon="uil:shopping-cart"></a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="product-item">
                                        <a href="#" class="btn-wishlist"><svg width="24" height="24">
                                                <use xlink:href="#heart"></use>
                                            </svg></a>
                                        <figure>
                                            <a href="index.html" title="Product Title">
                                                <img src="{{ asset('images/thumb-biscuits.png') }}" class="tab-image">
                                            </a>
                                        </figure>
                                        <h3>Sunstar Fresh Melon Juice</h3>
                                        <span class="qty">1 Unit</span><span class="rating"><svg width="24" height="24" class="text-primary">
                                                <use xlink:href="#star-solid"></use>
                                            </svg> 4.5</span>
                                        <span class="price">$18.00</span>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="input-group product-qty">
                                                <span class="input-group-btn">
                                                    <button type="button" class="quantity-left-minus btn btn-danger btn-number" data-type="minus">
                                                        <svg width="16" height="16">
                                                            <use xlink:href="#minus"></use>
                                                        </svg>
                                                    </button>
                                                </span>
                                                <input type="text" id="quantity" name="quantity" class="form-control input-number" value="1">
                                                <span class="input-group-btn">
                                                    <button type="button" class="quantity-right-plus btn btn-success btn-number" data-type="plus">
                                                        <svg width="16" height="16">
                                                            <use xlink:href="#plus"></use>
                                                        </svg>
                                                    </button>
                                                </span>
                                            </div>
                                            <a href="#" class="nav-link">Add to Cart <iconify-icon icon="uil:shopping-cart"></a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="product-item">
                                        <a href="#" class="btn-wishlist"><svg width="24" height="24">
                                                <use xlink:href="#heart"></use>
                                            </svg></a>
                                        <figure>
                                            <a href="index.html" title="Product Title">
                                                <img src="{{ asset('images/thumb-cucumber.png') }}" class="tab-image">
                                            </a>
                                        </figure>
                                        <h3>Sunstar Fresh Melon Juice</h3>
                                        <span class="qty">1 Unit</span><span class="rating"><svg width="24" height="24" class="text-primary">
                                                <use xlink:href="#star-solid"></use>
                                            </svg> 4.5</span>
                                        <span class="price">$18.00</span>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="input-group product-qty">
                                                <span class="input-group-btn">
                                                    <button type="button" class="quantity-left-minus btn btn-danger btn-number" data-type="minus">
                                                        <svg width="16" height="16">
                                                            <use xlink:href="#minus"></use>
                                                        </svg>
                                                    </button>
                                                </span>
                                                <input type="text" id="quantity" name="quantity" class="form-control input-number" value="1">
                                                <span class="input-group-btn">
                                                    <button type="button" class="quantity-right-plus btn btn-success btn-number" data-type="plus">
                                                        <svg width="16" height="16">
                                                            <use xlink:href="#plus"></use>
                                                        </svg>
                                                    </button>
                                                </span>
                                            </div>
                                            <a href="#" class="nav-link">Add to Cart <iconify-icon icon="uil:shopping-cart"></a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="product-item">
                                        <a href="#" class="btn-wishlist"><svg width="24" height="24">
                                                <use xlink:href="#heart"></use>
                                            </svg></a>
                                        <figure>
                                            <a href="index.html" title="Product Title">
                                                <img src="{{ asset('images/thumb-milk.png') }}" class="tab-image">
                                            </a>
                                        </figure>
                                        <h3>Sunstar Fresh Melon Juice</h3>
                                        <span class="qty">1 Unit</span><span class="rating"><svg width="24" height="24" class="text-primary">
                                                <use xlink:href="#star-solid"></use>
                                            </svg> 4.5</span>
                                        <span class="price">$18.00</span>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="input-group product-qty">
                                                <span class="input-group-btn">
                                                    <button type="button" class="quantity-left-minus btn btn-danger btn-number" data-type="minus">
                                                        <svg width="16" height="16">
                                                            <use xlink:href="#minus"></use>
                                                        </svg>
                                                    </button>
                                                </span>
                                                <input type="text" id="quantity" name="quantity" class="form-control input-number" value="1">
                                                <span class="input-group-btn">
                                                    <button type="button" class="quantity-right-plus btn btn-success btn-number" data-type="plus">
                                                        <svg width="16" height="16">
                                                            <use xlink:href="#plus"></use>
                                                        </svg>
                                                    </button>
                                                </span>
                                            </div>
                                            <a href="#" class="nav-link">Add to Cart <iconify-icon icon="uil:shopping-cart"></a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="product-item">
                                        <a href="#" class="btn-wishlist"><svg width="24" height="24">
                                                <use xlink:href="#heart"></use>
                                            </svg></a>
                                        <figure>
                                            <a href="index.html" title="Product Title">
                                                <img src="{{ asset('images/thumb-bananas.png') }}" class="tab-image">
                                            </a>
                                        </figure>
                                        <h3>Sunstar Fresh Melon Juice</h3>
                                        <span class="qty">1 Unit</span><span class="rating"><svg width="24" height="24" class="text-primary">
                                                <use xlink:href="#star-solid"></use>
                                            </svg> 4.5</span>
                                        <span class="price">$18.00</span>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="input-group product-qty">
                                                <span class="input-group-btn">
                                                    <button type="button" class="quantity-left-minus btn btn-danger btn-number" data-type="minus">
                                                        <svg width="16" height="16">
                                                            <use xlink:href="#minus"></use>
                                                        </svg>
                                                    </button>
                                                </span>
                                                <input type="text" id="quantity" name="quantity" class="form-control input-number" value="1">
                                                <span class="input-group-btn">
                                                    <button type="button" class="quantity-right-plus btn btn-success btn-number" data-type="plus">
                                                        <svg width="16" height="16">
                                                            <use xlink:href="#plus"></use>
                                                        </svg>
                                                    </button>
                                                </span>
                                            </div>
                                            <a href="#" class="nav-link">Add to Cart <iconify-icon icon="uil:shopping-cart"></a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="product-item">
                                        <a href="#" class="btn-wishlist"><svg width="24" height="24">
                                                <use xlink:href="#heart"></use>
                                            </svg></a>
                                        <figure>
                                            <a href="index.html" title="Product Title">
                                                <img src="{{ asset('images/thumb-biscuits.png') }}" class="tab-image">
                                            </a>
                                        </figure>
                                        <h3>Sunstar Fresh Melon Juice</h3>
                                        <span class="qty">1 Unit</span><span class="rating"><svg width="24" height="24" class="text-primary">
                                                <use xlink:href="#star-solid"></use>
                                            </svg> 4.5</span>
                                        <span class="price">$18.00</span>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="input-group product-qty">
                                                <span class="input-group-btn">
                                                    <button type="button" class="quantity-left-minus btn btn-danger btn-number" data-type="minus">
                                                        <svg width="16" height="16">
                                                            <use xlink:href="#minus"></use>
                                                        </svg>
                                                    </button>
                                                </span>
                                                <input type="text" id="quantity" name="quantity" class="form-control input-number" value="1">
                                                <span class="input-group-btn">
                                                    <button type="button" class="quantity-right-plus btn btn-success btn-number" data-type="plus">
                                                        <svg width="16" height="16">
                                                            <use xlink:href="#plus"></use>
                                                        </svg>
                                                    </button>
                                                </span>
                                            </div>
                                            <a href="#" class="nav-link">Add to Cart <iconify-icon icon="uil:shopping-cart"></a>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <!-- / product-grid -->

                        </div>

                        <div class="tab-pane fade" id="nav-fruits" role="tabpanel" aria-labelledby="nav-fruits-tab">

                            <div class="product-grid row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5">

                                <div class="col">
                                    <div class="product-item">
                                        <span class="badge bg-success position-absolute m-3">-30%</span>
                                        <a href="#" class="btn-wishlist"><svg width="24" height="24">
                                                <use xlink:href="#heart"></use>
                                            </svg></a>
                                        <figure>
                                            <a href="index.html" title="Product Title">
                                                <img src="{{ asset('images/thumb-cucumber.png') }}" class="tab-image">
                                            </a>
                                        </figure>
                                        <h3>Sunstar Fresh Melon Juice</h3>
                                        <span class="qty">1 Unit</span><span class="rating"><svg width="24" height="24" class="text-primary">
                                                <use xlink:href="#star-solid"></use>
                                            </svg> 4.5</span>
                                        <span class="price">$18.00</span>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="input-group product-qty">
                                                <span class="input-group-btn">
                                                    <button type="button" class="quantity-left-minus btn btn-danger btn-number" data-type="minus">
                                                        <svg width="16" height="16">
                                                            <use xlink:href="#minus"></use>
                                                        </svg>
                                                    </button>
                                                </span>
                                                <input type="text" id="quantity" name="quantity" class="form-control input-number" value="1">
                                                <span class="input-group-btn">
                                                    <button type="button" class="quantity-right-plus btn btn-success btn-number" data-type="plus">
                                                        <svg width="16" height="16">
                                                            <use xlink:href="#plus"></use>
                                                        </svg>
                                                    </button>
                                                </span>
                                            </div>
                                            <a href="#" class="nav-link">Add to Cart <iconify-icon icon="uil:shopping-cart"></a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="product-item">
                                        <span class="badge bg-success position-absolute m-3">-30%</span>
                                        <a href="#" class="btn-wishlist"><svg width="24" height="24">
                                                <use xlink:href="#heart"></use>
                                            </svg></a>
                                        <figure>
                                            <a href="index.html" title="Product Title">
                                                <img src="{{ asset('images/thumb-milk.png') }}" class="tab-image">
                                            </a>
                                        </figure>
                                        <h3>Sunstar Fresh Melon Juice</h3>
                                        <span class="qty">1 Unit</span><span class="rating"><svg width="24" height="24" class="text-primary">
                                                <use xlink:href="#star-solid"></use>
                                            </svg> 4.5</span>
                                        <span class="price">$18.00</span>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="input-group product-qty">
                                                <span class="input-group-btn">
                                                    <button type="button" class="quantity-left-minus btn btn-danger btn-number" data-type="minus">
                                                        <svg width="16" height="16">
                                                            <use xlink:href="#minus"></use>
                                                        </svg>
                                                    </button>
                                                </span>
                                                <input type="text" id="quantity" name="quantity" class="form-control input-number" value="1">
                                                <span class="input-group-btn">
                                                    <button type="button" class="quantity-right-plus btn btn-success btn-number" data-type="plus">
                                                        <svg width="16" height="16">
                                                            <use xlink:href="#plus"></use>
                                                        </svg>
                                                    </button>
                                                </span>
                                            </div>
                                            <a href="#" class="nav-link">Add to Cart <iconify-icon icon="uil:shopping-cart"></a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="product-item">
                                        <span class="badge bg-success position-absolute m-3">-30%</span>
                                        <a href="#" class="btn-wishlist"><svg width="24" height="24">
                                                <use xlink:href="#heart"></use>
                                            </svg></a>
                                        <figure>
                                            <a href="index.html" title="Product Title">
                                                <img src="{{ asset('images/thumb-orange-juice.png') }}" class="tab-image">
                                            </a>
                                        </figure>
                                        <h3>Sunstar Fresh Melon Juice</h3>
                                        <span class="qty">1 Unit</span><span class="rating"><svg width="24" height="24" class="text-primary">
                                                <use xlink:href="#star-solid"></use>
                                            </svg> 4.5</span>
                                        <span class="price">$18.00</span>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="input-group product-qty">
                                                <span class="input-group-btn">
                                                    <button type="button" class="quantity-left-minus btn btn-danger btn-number" data-type="minus">
                                                        <svg width="16" height="16">
                                                            <use xlink:href="#minus"></use>
                                                        </svg>
                                                    </button>
                                                </span>
                                                <input type="text" id="quantity" name="quantity" class="form-control input-number" value="1">
                                                <span class="input-group-btn">
                                                    <button type="button" class="quantity-right-plus btn btn-success btn-number" data-type="plus">
                                                        <svg width="16" height="16">
                                                            <use xlink:href="#plus"></use>
                                                        </svg>
                                                    </button>
                                                </span>
                                            </div>
                                            <a href="#" class="nav-link">Add to Cart <iconify-icon icon="uil:shopping-cart"></a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="product-item">
                                        <a href="#" class="btn-wishlist"><svg width="24" height="24">
                                                <use xlink:href="#heart"></use>
                                            </svg></a>
                                        <figure>
                                            <a href="index.html" title="Product Title">
                                                <img src="{{ asset('images/thumb-raspberries.png') }}" class="tab-image">
                                            </a>
                                        </figure>
                                        <h3>Sunstar Fresh Melon Juice</h3>
                                        <span class="qty">1 Unit</span><span class="rating"><svg width="24" height="24" class="text-primary">
                                                <use xlink:href="#star-solid"></use>
                                            </svg> 4.5</span>
                                        <span class="price">$18.00</span>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="input-group product-qty">
                                                <span class="input-group-btn">
                                                    <button type="button" class="quantity-left-minus btn btn-danger btn-number" data-type="minus">
                                                        <svg width="16" height="16">
                                                            <use xlink:href="#minus"></use>
                                                        </svg>
                                                    </button>
                                                </span>
                                                <input type="text" id="quantity" name="quantity" class="form-control input-number" value="1">
                                                <span class="input-group-btn">
                                                    <button type="button" class="quantity-right-plus btn btn-success btn-number" data-type="plus">
                                                        <svg width="16" height="16">
                                                            <use xlink:href="#plus"></use>
                                                        </svg>
                                                    </button>
                                                </span>
                                            </div>
                                            <a href="#" class="nav-link">Add to Cart <iconify-icon icon="uil:shopping-cart"></a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="product-item">
                                        <a href="#" class="btn-wishlist"><svg width="24" height="24">
                                                <use xlink:href="#heart"></use>
                                            </svg></a>
                                        <figure>
                                            <a href="index.html" title="Product Title">
                                                <img src="{{ asset('images/thumb-bananas.png') }}" class="tab-image">
                                            </a>
                                        </figure>
                                        <h3>Sunstar Fresh Melon Juice</h3>
                                        <span class="qty">1 Unit</span><span class="rating"><svg width="24" height="24" class="text-primary">
                                                <use xlink:href="#star-solid"></use>
                                            </svg> 4.5</span>
                                        <span class="price">$18.00</span>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="input-group product-qty">
                                                <span class="input-group-btn">
                                                    <button type="button" class="quantity-left-minus btn btn-danger btn-number" data-type="minus">
                                                        <svg width="16" height="16">
                                                            <use xlink:href="#minus"></use>
                                                        </svg>
                                                    </button>
                                                </span>
                                                <input type="text" id="quantity" name="quantity" class="form-control input-number" value="1">
                                                <span class="input-group-btn">
                                                    <button type="button" class="quantity-right-plus btn btn-success btn-number" data-type="plus">
                                                        <svg width="16" height="16">
                                                            <use xlink:href="#plus"></use>
                                                        </svg>
                                                    </button>
                                                </span>
                                            </div>
                                            <a href="#" class="nav-link">Add to Cart <iconify-icon icon="uil:shopping-cart"></a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="product-item">
                                        <a href="#" class="btn-wishlist"><svg width="24" height="24">
                                                <use xlink:href="#heart"></use>
                                            </svg></a>
                                        <figure>
                                            <a href="index.html" title="Product Title">
                                                <img src="{{ asset('images/thumb-bananas.png') }}" class="tab-image">
                                            </a>
                                        </figure>
                                        <h3>Sunstar Fresh Melon Juice</h3>
                                        <span class="qty">1 Unit</span><span class="rating"><svg width="24" height="24" class="text-primary">
                                                <use xlink:href="#star-solid"></use>
                                            </svg> 4.5</span>
                                        <span class="price">$18.00</span>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="input-group product-qty">
                                                <span class="input-group-btn">
                                                    <button type="button" class="quantity-left-minus btn btn-danger btn-number" data-type="minus">
                                                        <svg width="16" height="16">
                                                            <use xlink:href="#minus"></use>
                                                        </svg>
                                                    </button>
                                                </span>
                                                <input type="text" id="quantity" name="quantity" class="form-control input-number" value="1">
                                                <span class="input-group-btn">
                                                    <button type="button" class="quantity-right-plus btn btn-success btn-number" data-type="plus">
                                                        <svg width="16" height="16">
                                                            <use xlink:href="#plus"></use>
                                                        </svg>
                                                    </button>
                                                </span>
                                            </div>
                                            <a href="#" class="nav-link">Add to Cart <iconify-icon icon="uil:shopping-cart"></a>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <!-- / product-grid -->

                        </div>
                        <div class="tab-pane fade" id="nav-juices" role="tabpanel" aria-labelledby="nav-juices-tab">

                            <div class="product-grid row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5">

                                <div class="col">
                                    <div class="product-item">
                                        <a href="#" class="btn-wishlist"><svg width="24" height="24">
                                                <use xlink:href="#heart"></use>
                                            </svg></a>
                                        <figure>
                                            <a href="index.html" title="Product Title">
                                                <img src="{{ asset('images/thumb-cucumber.png') }}" class="tab-image">
                                            </a>
                                        </figure>
                                        <h3>Sunstar Fresh Melon Juice</h3>
                                        <span class="qty">1 Unit</span><span class="rating"><svg width="24" height="24" class="text-primary">
                                                <use xlink:href="#star-solid"></use>
                                            </svg> 4.5</span>
                                        <span class="price">$18.00</span>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="input-group product-qty">
                                                <span class="input-group-btn">
                                                    <button type="button" class="quantity-left-minus btn btn-danger btn-number" data-type="minus">
                                                        <svg width="16" height="16">
                                                            <use xlink:href="#minus"></use>
                                                        </svg>
                                                    </button>
                                                </span>
                                                <input type="text" id="quantity" name="quantity" class="form-control input-number" value="1">
                                                <span class="input-group-btn">
                                                    <button type="button" class="quantity-right-plus btn btn-success btn-number" data-type="plus">
                                                        <svg width="16" height="16">
                                                            <use xlink:href="#plus"></use>
                                                        </svg>
                                                    </button>
                                                </span>
                                            </div>
                                            <a href="#" class="nav-link">Add to Cart <iconify-icon icon="uil:shopping-cart"></a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="product-item">
                                        <a href="#" class="btn-wishlist"><svg width="24" height="24">
                                                <use xlink:href="#heart"></use>
                                            </svg></a>
                                        <figure>
                                            <a href="index.html" title="Product Title">
                                                <img src="{{ asset('images/thumb-milk.png') }}" class="tab-image">
                                            </a>
                                        </figure>
                                        <h3>Sunstar Fresh Melon Juice</h3>
                                        <span class="qty">1 Unit</span><span class="rating"><svg width="24" height="24" class="text-primary">
                                                <use xlink:href="#star-solid"></use>
                                            </svg> 4.5</span>
                                        <span class="price">$18.00</span>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="input-group product-qty">
                                                <span class="input-group-btn">
                                                    <button type="button" class="quantity-left-minus btn btn-danger btn-number" data-type="minus">
                                                        <svg width="16" height="16">
                                                            <use xlink:href="#minus"></use>
                                                        </svg>
                                                    </button>
                                                </span>
                                                <input type="text" id="quantity" name="quantity" class="form-control input-number" value="1">
                                                <span class="input-group-btn">
                                                    <button type="button" class="quantity-right-plus btn btn-success btn-number" data-type="plus">
                                                        <svg width="16" height="16">
                                                            <use xlink:href="#plus"></use>
                                                        </svg>
                                                    </button>
                                                </span>
                                            </div>
                                            <a href="#" class="nav-link">Add to Cart <iconify-icon icon="uil:shopping-cart"></a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="product-item">
                                        <a href="#" class="btn-wishlist"><svg width="24" height="24">
                                                <use xlink:href="#heart"></use>
                                            </svg></a>
                                        <figure>
                                            <a href="index.html" title="Product Title">
                                                <img src="{{ asset('images/thumb-tomatoes.png') }}" class="tab-image">
                                            </a>
                                        </figure>
                                        <h3>Sunstar Fresh Melon Juice</h3>
                                        <span class="qty">1 Unit</span><span class="rating"><svg width="24" height="24" class="text-primary">
                                                <use xlink:href="#star-solid"></use>
                                            </svg> 4.5</span>
                                        <span class="price">$18.00</span>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="input-group product-qty">
                                                <span class="input-group-btn">
                                                    <button type="button" class="quantity-left-minus btn btn-danger btn-number" data-type="minus">
                                                        <svg width="16" height="16">
                                                            <use xlink:href="#minus"></use>
                                                        </svg>
                                                    </button>
                                                </span>
                                                <input type="text" id="quantity" name="quantity" class="form-control input-number" value="1">
                                                <span class="input-group-btn">
                                                    <button type="button" class="quantity-right-plus btn btn-success btn-number" data-type="plus">
                                                        <svg width="16" height="16">
                                                            <use xlink:href="#plus"></use>
                                                        </svg>
                                                    </button>
                                                </span>
                                            </div>
                                            <a href="#" class="nav-link">Add to Cart <iconify-icon icon="uil:shopping-cart"></a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="product-item">
                                        <a href="#" class="btn-wishlist"><svg width="24" height="24">
                                                <use xlink:href="#heart"></use>
                                            </svg></a>
                                        <figure>
                                            <a href="index.html" title="Product Title">
                                                <img src="{{ asset('images/thumb-tomatoketchup.png') }}" class="tab-image">
                                            </a>
                                        </figure>
                                        <h3>Sunstar Fresh Melon Juice</h3>
                                        <span class="qty">1 Unit</span><span class="rating"><svg width="24" height="24" class="text-primary">
                                                <use xlink:href="#star-solid"></use>
                                            </svg> 4.5</span>
                                        <span class="price">$18.00</span>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="input-group product-qty">
                                                <span class="input-group-btn">
                                                    <button type="button" class="quantity-left-minus btn btn-danger btn-number" data-type="minus">
                                                        <svg width="16" height="16">
                                                            <use xlink:href="#minus"></use>
                                                        </svg>
                                                    </button>
                                                </span>
                                                <input type="text" id="quantity" name="quantity" class="form-control input-number" value="1">
                                                <span class="input-group-btn">
                                                    <button type="button" class="quantity-right-plus btn btn-success btn-number" data-type="plus">
                                                        <svg width="16" height="16">
                                                            <use xlink:href="#plus"></use>
                                                        </svg>
                                                    </button>
                                                </span>
                                            </div>
                                            <a href="#" class="nav-link">Add to Cart <iconify-icon icon="uil:shopping-cart"></a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="product-item">
                                        <a href="#" class="btn-wishlist"><svg width="24" height="24">
                                                <use xlink:href="#heart"></use>
                                            </svg></a>
                                        <figure>
                                            <a href="index.html" title="Product Title">
                                                <img src="{{ asset('images/thumb-bananas.png') }}" class="tab-image">
                                            </a>
                                        </figure>
                                        <h3>Sunstar Fresh Melon Juice</h3>
                                        <span class="qty">1 Unit</span><span class="rating"><svg width="24" height="24" class="text-primary">
                                                <use xlink:href="#star-solid"></use>
                                            </svg> 4.5</span>
                                        <span class="price">$18.00</span>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="input-group product-qty">
                                                <span class="input-group-btn">
                                                    <button type="button" class="quantity-left-minus btn btn-danger btn-number" data-type="minus">
                                                        <svg width="16" height="16">
                                                            <use xlink:href="#minus"></use>
                                                        </svg>
                                                    </button>
                                                </span>
                                                <input type="text" id="quantity" name="quantity" class="form-control input-number" value="1">
                                                <span class="input-group-btn">
                                                    <button type="button" class="quantity-right-plus btn btn-success btn-number" data-type="plus">
                                                        <svg width="16" height="16">
                                                            <use xlink:href="#plus"></use>
                                                        </svg>
                                                    </button>
                                                </span>
                                            </div>
                                            <a href="#" class="nav-link">Add to Cart <iconify-icon icon="uil:shopping-cart"></a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="product-item">
                                        <a href="#" class="btn-wishlist"><svg width="24" height="24">
                                                <use xlink:href="#heart"></use>
                                            </svg></a>
                                        <figure>
                                            <a href="index.html" title="Product Title">
                                                <img src="{{ asset('images/thumb-bananas.png') }}" class="tab-image">
                                            </a>
                                        </figure>
                                        <h3>Sunstar Fresh Melon Juice</h3>
                                        <span class="qty">1 Unit</span><span class="rating"><svg width="24" height="24" class="text-primary">
                                                <use xlink:href="#star-solid"></use>
                                            </svg> 4.5</span>
                                        <span class="price">$18.00</span>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="input-group product-qty">
                                                <span class="input-group-btn">
                                                    <button type="button" class="quantity-left-minus btn btn-danger btn-number" data-type="minus">
                                                        <svg width="16" height="16">
                                                            <use xlink:href="#minus"></use>
                                                        </svg>
                                                    </button>
                                                </span>
                                                <input type="text" id="quantity" name="quantity" class="form-control input-number" value="1">
                                                <span class="input-group-btn">
                                                    <button type="button" class="quantity-right-plus btn btn-success btn-number" data-type="plus">
                                                        <svg width="16" height="16">
                                                            <use xlink:href="#plus"></use>
                                                        </svg>
                                                    </button>
                                                </span>
                                            </div>
                                            <a href="#" class="nav-link">Add to Cart <iconify-icon icon="uil:shopping-cart"></a>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <!-- / product-grid -->

                        </div>

                    </div>
                </div>

            </div>
        </div>

        {{-- Row 2 - hàng 2 có 3 cột --}}
        <div class="row">
            <div class="col-12 col-md-4 mb-3">Cot 1</div>
            <div class="col-12 col-md-4 mb-3">Cot 2</div>
            <div class="col-12 col-md-4 mb-3">Cot 3</div>
        </div>
    </div>
</section>


@endsection

@push('scripts')
<script>
    // JS riêng trang này - demo thôi nha
    let tempHeader = document.querySelector('.temp-header');
    tempHeader.addEventListener('mouseenter', function() {
        tempHeader.style.color = 'red';
    });
    tempHeader.addEventListener('mouseleave', function() {
        tempHeader.style.color = '#007bff';
    });
</script>
@endpush