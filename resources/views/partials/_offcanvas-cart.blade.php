<div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1" id="offcanvasCart" aria-labelledby="My Cart">
    <div class="offcanvas-header justify-content-center">
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>

    <div class="offcanvas-body">
        <div class="order-md-last">

            <!-- HEADER -->
            <h4 class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-primary redtitle">Your cart</span>
                <span class="badge bg-primary rounded-pill redbackground">
                    {{ $cartCount }}
                </span>
            </h4>

            <!-- ITEMS -->
            <ul class="list-group mb-3" id="offcanvasCartItems">
                @forelse($cart->items as $item)
                <li class="list-group-item d-flex justify-content-between lh-sm">
                    <div>
                        <h6 class="my-0">
                            {{ $item->product->title }}
                        </h6>

                        @if ($item->variant)
                        <small class="text-body-secondary">
                            {{ $item->variant->name }}
                        </small>
                        @endif

                        <small class="text-body-secondary d-block">
                            SL: {{ $item->quantity }}
                        </small>
                    </div>

                    <span class="text-body-secondary">
                        {{ number_format($item->price_snapshot * $item->quantity, 0, ',', '.') }}đ
                    </span>
                </li>
                @empty
                <li class="list-group-item text-center py-4">
                    Giỏ hàng đang trống
                </li>
                @endforelse
            </ul>

            <!-- TOTAL ROW (ẩn nếu chưa có item) -->
            <div class="d-flex justify-content-between mb-3 {{ $cartCount > 0 ? '' : 'd-none' }}"
                id="offcanvasCartTotalRow">
                <span>Total (VND)</span>
                <strong id="offcanvasCartTotalText">
                    {{ number_format($cartTotal, 0, ',', '.') }}đ
                </strong>
            </div>

            <!-- BUTTON (ẩn nếu chưa có item) -->
            <a href="{{ route('cart.index') }}"
                class="w-100 btn btn-primary btn-lg redbackground {{ $cartCount > 0 ? '' : 'd-none' }}"
                id="offcanvasCheckoutBtn">
                Continue to checkout
            </a>

        </div>
    </div>
</div>