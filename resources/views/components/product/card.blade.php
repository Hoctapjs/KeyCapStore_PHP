@props([
'title', 'image', 'qty' => '1 Unit', 'rating' => 4.5, 'price',
'badge' => null, 'href' => '#'
])
<div class="product-item">
    @if($badge)<span class="badge bg-success position-absolute m-3">{{ $badge }}</span>@endif
    <a href="#" class="btn-wishlist"><svg width="24" height="24">
            <use xlink:href="#heart" />
        </svg></a>
    <figure><a href="{{ $href }}"><img src="{{ $image }}" class="tab-image"></a></figure>
    <h3>{{ $title }}</h3>
    <span class="qty">{{ $qty }}</span>
    <span class="rating"><svg width="24" height="24" class="text-primary">
            <use xlink:href="#star-solid" />
        </svg> {{ $rating }}</span>
    <span class="price">{{ $price }}</span>
    <div class="d-flex align-items-center justify-content-between">
        <div class="input-group product-qty">
            <button type="button" class="quantity-left-minus btn btn-danger btn-number" data-type="minus">
                <svg width="16" height="16">
                    <use xlink:href="#minus" />
                </svg>
            </button>
            <input type="text" class="form-control input-number" value="1">
            <button type="button" class="quantity-right-plus btn btn-success btn-number" data-type="plus">
                <svg width="16" height="16">
                    <use xlink:href="#plus" />
                </svg>
            </button>
        </div>
        <a href="#" class="nav-link">Thêm vào giỏ <iconify-icon icon="uil:shopping-cart"></iconify-icon></a>
    </div>
</div>

<!-- dùng trong view:
<x-product.card
  title="Sunstar Fresh Melon Juice"
  :image="asset('images/thumb-bananas.png')"
  price="$18.00"
  badge="-30%"
/> -->