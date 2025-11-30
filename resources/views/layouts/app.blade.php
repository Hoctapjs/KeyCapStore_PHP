<!DOCTYPE html>
<html lang="en">

<head>
    @include('partials._head')
    <style>
        .redtitle {
            color: #73c94c !important;
        }

        .redbackground {
            background-color: #73c94c !important;
            color: white;
        }
    </style>
</head>

<body>

    @include('partials._svg-sprite')
    @include('partials._preloader')

    @include('partials._offcanvas-cart')
    @include('partials._offcanvas-search')

    @include('partials._header')

    <div class="container-fluid">
        @yield('content')
    </div>


    @include('partials._footer')
    @include('partials._footer-bottom')

    {{-- JS local files for faster loading --}}
    <script src="{{ asset('vendor/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap.bundle.min.js') }}"></script>

    <script src="{{ asset('js/jquery-1.11.0.min.js') }}"></script>
    <script src="{{ asset('js/plugins.js') }}"></script>
    <script src="{{ asset('js/script.js') }}"></script>

    <!-- <script>
        function updateCartUI() {
            $.get("{{ route('cart.ajax.info') }}", function(data) {

                $(".dropdown-toggle").text(`Your Cart (${data.cartCount})`);
                $(".cart-total").text(data.cartTotal);
                $("#offcanvasCart .badge").text(data.cartCount);

                let html = "";
                if (data.items.length === 0) {
                    html = `<li class="list-group-item text-center py-4">Giỏ hàng đang trống</li>`;
                } else {
                    data.items.forEach(item => {
                        html += `
                <li class="list-group-item d-flex justify-content-between lh-sm">
                    <div>
                        <h6 class="my-0">${item.title}</h6>
                        ${item.variant ? `<small>${item.variant}</small>` : ""}
                        <small class="d-block">SL: ${item.quantity}</small>
                    </div>
                    <span>${item.total}</span>
                </li>`;
                    });
                }

                $("#offcanvasCart ul.list-group").html(html);
                $("#offcanvasCart strong").text(data.cartTotal);
            });
        }
    </script> -->
    <script>
        function updateCartUI() {
            $.get("{{ route('cart.ajax.info') }}", function(data) {

                // cập nhật header nhỏ trên navbar
                $(".dropdown-toggle").text(`Your Cart (${data.cartCount})`);
                $(".cart-total").text(data.cartTotal);
                $("#offcanvasCart .badge").text(data.cartCount);

                // ... rest of cart update code
            });
        }

        // Update wishlist count
        function updateWishlistCount() {
            $.get("{{ route('wishlist.count') }}", function(data) {
                $('.wishlist-count').text(data.count);
                if (data.count > 0) {
                    $('.wishlist-count').show();
                } else {
                    $('.wishlist-count').hide();
                }
            });
        }

        // Load wishlist count on page load
        $(document).ready(function() {
            updateWishlistCount();
        });
    </script>

                // build HTML item trong offcanvas
                let html = "";
                if (data.items.length === 0) {
                    html = `<li class="list-group-item text-center py-4">Giỏ hàng đang trống</li>`;
                } else {
                    data.items.forEach(item => {
                        html += `
                                <li class="list-group-item d-flex justify-content-between lh-sm">
                                    <div>
                                        <h6 class="my-0">${item.title}</h6>
                                        ${item.variant ? `<small class="text-body-secondary">${item.variant}</small>` : ""}
                                        <small class="text-body-secondary d-block">SL: ${item.quantity}</small>
                                    </div>
                                    <span class="text-body-secondary">${item.total}</span>
                                </li>`;
                    });
                }

                // chỉ thay phần items
                $("#offcanvasCartItems").html(html);

                // show/hide total + update tổng
                if (data.cartCount > 0) {
                    $("#offcanvasCartTotalRow").removeClass("d-none");
                    $("#offcanvasCartTotalText").text(data.cartTotal);
                    $("#offcanvasCheckoutBtn").removeClass("d-none");
                } else {
                    $("#offcanvasCartTotalRow").addClass("d-none");
                    $("#offcanvasCheckoutBtn").addClass("d-none");
                }
            });
        }
    </script>



    @stack('scripts')
</body>

</html>