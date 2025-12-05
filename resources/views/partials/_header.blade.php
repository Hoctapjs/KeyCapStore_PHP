<header>
    <div class="container-fluid fixed-top bg-white shadow-sm" style="z-index: 9999;">
        <div class="row py-3 border-bottom">

            <div class="col-sm-4 col-lg-3 text-center text-sm-start">
                <div class="main-logo">
                    <a href="{{ route('home') }}">
                        <!-- <img src="images/logo.png" alt="logo" class="img-fluid"> -->
                        <img src="{{ asset('images/logo.png') }}" alt="logo" class="img-fluid" width="80%">
                    </a>
                </div>
            </div>

            <div class="col-sm-6 offset-sm-2 offset-md-0 col-lg-5 d-none d-lg-block">
                <div class="search-bar row bg-light p-2 my-2 rounded-4 position-relative">
                    <div class="col-md-4 d-none d-md-block">
                        <select class="form-select border-0 bg-transparent" id="search-category">
                            <option value="">Tất cả</option>
                            @php
                                $searchCategories = \App\Models\Category::whereNull('parent_id')->get();
                            @endphp
                            @foreach($searchCategories as $cat)
                                <option value="{{ $cat->slug }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-11 col-md-7">
                        <form id="search-form" class="text-center" action="{{ route('products.index') }}" method="GET">
                            <input type="text"
                                name="search"
                                id="search-input"
                                value="{{ request('search') }}"
                                class="form-control border-0 bg-transparent"
                                placeholder="Tìm kiếm sản phẩm..."
                                autocomplete="off" />
                        </form>
                    </div>

                    <div class="col-1 search-submit-btn" style="cursor: pointer;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M21.71 20.29L18 16.61A9 9 0 1 0 16.61 18l3.68 3.68a1 1 0 0 0 1.42 0a1 1 0 0 0 0-1.39ZM11 18a7 7 0 1 1 7-7a7 7 0 0 1-7 7Z" />
                        </svg>
                    </div>

                    <!-- Search Suggestions Dropdown -->
                    <div id="search-suggestions" class="search-suggestions-dropdown" style="display: none;">
                        <div class="suggestions-content">
                            <!-- Categories Section -->
                            <div id="suggestions-categories" class="suggestions-section" style="display: none;">
                                <div class="suggestions-title">Danh mục</div>
                                <div class="suggestions-list" id="categories-list"></div>
                            </div>

                            <!-- Brands Section -->
                            <div id="suggestions-brands" class="suggestions-section" style="display: none;">
                                <div class="suggestions-title">Thương hiệu</div>
                                <div class="suggestions-list" id="brands-list"></div>
                            </div>

                            <!-- Products Section -->
                            <div id="suggestions-products" class="suggestions-section" style="display: none;">
                                <div class="suggestions-title">Sản phẩm gợi ý</div>
                                <div class="suggestions-products-list" id="products-list"></div>
                            </div>

                            <!-- No Results -->
                            <div id="no-suggestions" class="text-center py-3 text-muted" style="display: none;">
                                Không tìm thấy kết quả
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-8 col-lg-4 d-flex justify-content-end gap-5 align-items-center mt-4 mt-sm-0 justify-content-center justify-content-sm-end">
                <!-- <div class="support-box text-end d-none d-xl-block">
                        <span class="fs-6 text-muted">For Support?</span>
                        <h5 class="mb-0">+980-34984089</h5>
                    </div> -->

                <div class="support-box text-end d-none d-xl-block">
                    @auth
                    <span class="fs-6 text-muted">Chào mừng,</span>
                    <h5 class="mb-0">{{ Auth::user()->name }}</h5>
                    @endauth
                    @guest
                    <!-- Có thể để trống, hoặc hiển thị link đăng nhập -->
                    <span class="mb-0"><a href="{{ route('login.form') }}" class="text-decoration-none text-dark">Đăng nhập</a></span>/<span class="mb-0"><a href="{{ route('register.form') }}" class="text-decoration-none text-dark">Đăng ký</a></span>
                    @endguest
                </div>

                <ul class="d-flex justify-content-end list-unstyled m-0">
                    <!-- <li>
                            <a href="#" class="rounded-circle bg-light p-2 mx-1">
                                <svg width="24" height="24" viewBox="0 0 24 24">
                                    <use xlink:href="#user"></use>
                                </svg>
                            </a>
                        </li> -->
                    <li class="nav-item dropdown">
                        <a href="#" class="rounded-circle bg-light p-2 mx-1" id="userAccountDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <svg width="24" height="24" viewBox="0 0 24 24">
                                <use xlink:href="#user"></use>
                            </svg>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userAccountDropdown">
                            @auth
                            <!-- <li><span class="dropdown-item-text">Chào, {{ Auth::user()->name }}</span></li> -->
                            <!-- <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li> -->
                            <li>
                                <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-black">Đăng xuất</button>
                                </form>
                            </li>
                            <!-- <li>
                                <button type="submit" class="dropdown-item text-black">Hồ sơ người dùng</button>
                            </li> -->
                            <li>
                                <a href="{{ route('account.profile') }}" class="dropdown-item text-black">
                                    Hồ sơ người dùng
                                </a>
                            </li>
                            <!-- <li>
                                <a href="{{ route('addresses.index') }}" class="dropdown-item text-black">
                                    Sổ Địa Chỉ
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('account.password') }}" class="dropdown-item text-black">
                                    Đổi Mật Khẩu
                                </a>
                            </li> -->
                            @endauth

                            @guest
                            <li><a class="dropdown-item" href="{{ route('login.form') }}">Đăng nhập</a></li>
                            <li><a class="dropdown-item" href="{{ route('register.form') }}">Đăng ký</a></li>
                            @endguest
                        </ul>
                    </li>
                    <li>
                        <a href="{{ route('wishlist.index') }}" class="rounded-circle bg-light p-2 mx-1 position-relative" title="Danh sách yêu thích">
                            <svg width="24" height="24" viewBox="0 0 24 24">
                                <use xlink:href="#heart"></use>
                            </svg>
                            @auth
                            @php
                            $wishlistCount = \App\Models\Wishlist::where('user_id', Auth::id())->count();
                            @endphp
                            @if($wishlistCount > 0)
                            <span class="wishlist-count position-absolute top-0 start-100 translate-middle badge rounded-pill" style="font-size: 0.65rem; background-color: #dc3545 !important;">
                                {{ $wishlistCount }}
                            </span>
                            @endif
                            @endauth
                        </a>
                    </li>
                    <li class="d-lg-none">
                        <a href="#" class="rounded-circle bg-light p-2 mx-1" data-bs-toggle="offcanvas" data-bs-target="#offcanvasCart" aria-controls="offcanvasCart">
                            <svg width="24" height="24" viewBox="0 0 24 24">
                                <use xlink:href="#cart"></use>
                            </svg>
                        </a>
                    </li>
                    <li class="d-lg-none">
                        <a href="#" class="rounded-circle bg-light p-2 mx-1" data-bs-toggle="offcanvas" data-bs-target="#offcanvasSearch" aria-controls="offcanvasSearch">
                            <svg width="24" height="24" viewBox="0 0 24 24">
                                <use xlink:href="#search"></use>
                            </svg>
                        </a>
                    </li>
                </ul>

                <!-- <div class="cart text-end d-none d-lg-block dropdown">
                    <button class="border-0 bg-transparent d-flex flex-column gap-2 lh-1" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasCart" aria-controls="offcanvasCart">
                        <span class="fs-6 text-muted dropdown-toggle">Your Cart</span>
                        <span class="cart-total fs-5 fw-bold">$1290.00</span>
                    </button>
                </div> -->
                @php
                use App\Models\Cart;
                use Illuminate\Support\Facades\Auth;

                // Nếu controller không truyền vào thì tự tính
                if (!isset($cart) || !isset($cartCount) || !isset($cartTotal)) {
                if (Auth::check()) {
                $cart = Cart::firstOrCreate(
                ['user_id' => Auth::id()]
                )->load(['items.product', 'items.variant']);
                } else {
                $sessionId = session()->getId();
                $cart = Cart::firstOrCreate(
                ['session_id' => $sessionId]
                )->load(['items.product', 'items.variant']);
                }

                $cartCount = $cart->items->sum('quantity');
                $cartTotal = $cart->items->sum(function ($item) {
                return $item->quantity * $item->price_snapshot;
                });
                }
                @endphp
                <div class="cart text-end d-none d-lg-block dropdown">
                    <button class="border-0 bg-transparent d-flex flex-column gap-2 lh-1"
                        type="button"
                        data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasCart"
                        aria-controls="offcanvasCart">

                        <span class="fs-6 text-muted dropdown-toggle">Your Cart ({{ $cartCount }})</span>

                        <span class="cart-total fs-5 fw-bold">
                            {{ number_format($cartTotal, 0, ',', '.') }}đ
                        </span>
                    </button>
                </div>
            </div>

        </div>
    </div>
    <!-- <div class="container-fluid">
        <div class="row py-3">
            <div class="d-flex  justify-content-center justify-content-sm-between align-items-center">
                <nav class="main-menu d-flex navbar navbar-expand-lg">

                    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
                        aria-controls="offcanvasNavbar">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">

                        <div class="offcanvas-header justify-content-center">
                            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                        </div>

                        <div class="offcanvas-body">

                            <select class="filter-categories border-0 mb-0 me-5">
                                <option>Shop by Departments</option>
                                <option>Groceries</option>
                                <option>Drinks</option>
                                <option>Chocolates</option>
                            </select>

                            <ul class="navbar-nav justify-content-end menu-list list-unstyled d-flex gap-md-3 mb-0">
                                <li class="nav-item active">
                                    <a href="#women" class="nav-link">Women</a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a href="#men" class="nav-link">Men</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#kids" class="nav-link">Kids</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#accessories" class="nav-link">Accessories</a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" role="button" id="pages" data-bs-toggle="dropdown" aria-expanded="false">Pages</a>
                                    <ul class="dropdown-menu" aria-labelledby="pages">
                                        <li><a href="index.html" class="dropdown-item">About Us </a></li>
                                        <li><a href="index.html" class="dropdown-item">Shop </a></li>
                                        <li><a href="index.html" class="dropdown-item">Single Product </a></li>
                                        <li><a href="index.html" class="dropdown-item">Cart </a></li>
                                        <li><a href="index.html" class="dropdown-item">Checkout </a></li>
                                        <li><a href="index.html" class="dropdown-item">Blog </a></li>
                                        <li><a href="index.html" class="dropdown-item">Single Post </a></li>
                                        <li><a href="index.html" class="dropdown-item">Styles </a></li>
                                        <li><a href="index.html" class="dropdown-item">Contact </a></li>
                                        <li><a href="index.html" class="dropdown-item">Thank You </a></li>
                                        <li><a href="index.html" class="dropdown-item">My Account </a></li>
                                        <li><a href="index.html" class="dropdown-item">404 Error </a></li>
                                    </ul>
                                </li>
                                <li class="nav-item">
                                    <a href="#brand" class="nav-link">Brand</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#sale" class="nav-link">Sale</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#blog" class="nav-link">Blog</a>
                                </li>
                            </ul>

                        </div>

                    </div>
            </div>
        </div>
    </div> -->

    <div class="container-fluid" style="margin-top: 120px;">
        <div class="row py-3">
            <div class="d-flex  justify-content-center justify-content-sm-between align-items-center">
                <nav class="main-menu d-flex navbar navbar-expand-lg">

                    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
                        aria-controls="offcanvasNavbar">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">

                        <div class="offcanvas-header justify-content-center">
                            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                        </div>

                        <div class="offcanvas-body">

                            <select class="filter-categories border-0 mb-0 me-5">
                                <option>Danh mục sản phẩm</option>
                                <option>Keycaps</option>
                                <option>Switches (Công tắc)</option>
                                <option>Keyboard Kits (Phím)</option>
                                <option>Phụ kiện (Accessories)</option>
                                <option>Artisan Keycaps</option>
                                <option>Desk Mats (Lót chuột)</option>
                            </select>

                            <ul class="navbar-nav justify-content-end menu-list list-unstyled d-flex gap-md-3 mb-0">

                                <li class="nav-item active">
                                    <a href="/" class="nav-link">Trang chủ</a>
                                </li>

                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">Keycaps</a>
                                    <ul class="dropdown-menu">
                                        <li><a href="/keycaps-profile-cherry" class="dropdown-item">Profile: Cherry</a></li>
                                        <li><a href="/keycaps-profile-sa" class="dropdown-item">Profile: SA</a></li>
                                        <li><a href="/keycaps-profile-xda" class="dropdown-item">Profile: XDA</a></li>
                                        <li><a href="/keycaps-pbt" class="dropdown-item">Chất liệu: PBT</a></li>
                                        <li><a href="/keycaps-abs" class="dropdown-item">Chất liệu: ABS</a></li>
                                        <li><a href="/keycaps-artisan" class="dropdown-item">Artisan Keycaps</a></li>
                                    </ul>
                                </li>

                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">Switches</a>
                                    <ul class="dropdown-menu">
                                        <li><a href="/products?category=linear-switches" class="dropdown-item">Linear</a></li>
                                        <li><a href="/products?category=tactile-switches" class="dropdown-item">Tactile</a></li>
                                        <li><a href="/products?category=clicky-switches" class="dropdown-item">Clicky</a></li>
                                    </ul>
                                </li>

                                <!-- <li class="nav-item">
                                    <a href="/accessories" class="nav-link">Phụ kiện</a>
                                </li> -->

                                <li class="nav-item">
                                    <a href="/about" class="nav-link">Về chúng tôi</a>
                                </li>

                                <li class="nav-item">
                                    <a href="/contact" class="nav-link">Liên hệ</a>
                                </li>

                                <!-- <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" role="button" id="pages" data-bs-toggle="dropdown" aria-expanded="false">Trang</a>
                                    <ul class="dropdown-menu" aria-labelledby="pages">
                                        <li><a href="about.html" class="dropdown-item">Về chúng tôi</a></li>
                                        <li><a href="shop.html" class="dropdown-item">Cửa hàng</a></li>
                                        <li><a href="cart.html" class="dropdown-item">Giỏ hàng</a></li>
                                        <li><a href="checkout.html" class="dropdown-item">Thanh toán</a></li>
                                        <li><a href="account.html" class="dropdown-item">Tài khoản</a></li>
                                        <li><a href="contact.html" class="dropdown-item">Liên hệ</a></li>
                                    </ul>
                                </li> -->

                                <!-- <li class="nav-item">
                                    <a href="/sale" class="nav-link">Sale</a>
                                </li>

                                <li class="nav-item">
                                    <a href="/blog" class="nav-link">Blog</a>
                                </li> -->
                            </ul>

                        </div>

                    </div>
                </nav>
            </div>
        </div>
    </div>
</header>