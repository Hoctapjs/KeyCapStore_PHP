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
        
        /* Search Suggestions Styles */
        .search-suggestions-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            z-index: 1000;
            max-height: 500px;
            overflow-y: auto;
            margin-top: 5px;
        }
        
        .suggestions-content {
            padding: 10px 0;
        }
        
        .suggestions-section {
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        
        .suggestions-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
        
        .suggestions-title {
            font-size: 12px;
            font-weight: 600;
            color: #666;
            padding: 5px 15px;
            text-transform: uppercase;
        }
        
        .suggestions-list a {
            display: block;
            padding: 8px 15px;
            color: #333;
            text-decoration: none;
            transition: background 0.2s;
        }
        
        .suggestions-list a:hover {
            background: #f5f5f5;
        }
        
        .suggestions-list a i {
            margin-right: 8px;
            color: #666;
        }
        
        .suggestions-products-list {
            padding: 0 10px;
        }
        
        .suggestion-product {
            display: flex;
            align-items: center;
            padding: 10px;
            border-radius: 8px;
            text-decoration: none;
            color: #333;
            transition: background 0.2s;
        }
        
        .suggestion-product:hover {
            background: #f5f5f5;
        }
        
        .suggestion-product img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 6px;
            margin-right: 12px;
        }
        
        .suggestion-product-info {
            flex: 1;
        }
        
        .suggestion-product-title {
            font-size: 14px;
            font-weight: 500;
            color: #333;
            margin-bottom: 4px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .suggestion-product-price {
            font-size: 14px;
            font-weight: 600;
            color: #dc3545;
        }
        
        .search-loading {
            text-align: center;
            padding: 20px;
        }
        
        .search-loading i {
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
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

    @include('partials.chat_widget')

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
        
        // Search Suggestions
        let searchTimeout;
        $(document).ready(function() {
            const $searchInput = $('#search-input');
            const $searchForm = $('#search-form');
            const $suggestions = $('#search-suggestions');
            const $categorySelect = $('#search-category');
            
            // Handle form submit - add category if selected
            $searchForm.on('submit', function(e) {
                e.preventDefault(); // Prevent default first
                const category = $categorySelect.val();
                console.log('Form submit, category:', category);
                // Remove any existing category input
                $searchForm.find('input[name="category"]').remove();
                // Only add category if it has a value
                if (category) {
                    $searchForm.append('<input type="hidden" name="category" value="' + category + '">');
                }
                // Now submit the form
                this.submit();
            });
            
            // Also handle icon click
            $('.search-submit-btn').on('click', function(e) {
                e.preventDefault();
                $searchForm.trigger('submit');
            });
            
            // Re-fetch suggestions when category changes
            $categorySelect.on('change', function() {
                const query = $searchInput.val().trim();
                if (query.length >= 2) {
                    fetchSuggestions(query);
                }
            });
            
            // Handle search input
            $searchInput.on('input', function() {
                const query = $(this).val().trim();
                
                clearTimeout(searchTimeout);
                
                if (query.length < 2) {
                    $suggestions.hide();
                    return;
                }
                
                searchTimeout = setTimeout(function() {
                    fetchSuggestions(query);
                }, 300);
            });
            
            // Hide suggestions when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.search-bar').length) {
                    $suggestions.hide();
                }
            });
            
            // Show suggestions on focus if there's text
            $searchInput.on('focus', function() {
                const query = $(this).val().trim();
                if (query.length >= 2) {
                    // Re-fetch suggestions when focusing on input with existing text
                    fetchSuggestions(query);
                }
            });
            
            // Handle Enter key
            $searchInput.on('keydown', function(e) {
                if (e.key === 'Enter') {
                    $suggestions.hide();
                }
            });
            
            function fetchSuggestions(query) {
                const category = $categorySelect.val();
                $.ajax({
                    url: '{{ route("search.suggestions") }}',
                    type: 'GET',
                    data: { 
                        q: query,
                        category: category 
                    },
                    success: function(response) {
                        renderSuggestions(response, query);
                    },
                    error: function() {
                        $suggestions.hide();
                    }
                });
            }
            
            function renderSuggestions(data, query) {
                const hasCategories = data.categories && data.categories.length > 0;
                const hasBrands = data.brands && data.brands.length > 0;
                const hasProducts = data.products && data.products.length > 0;
                
                if (!hasCategories && !hasBrands && !hasProducts) {
                    $('#suggestions-categories, #suggestions-brands, #suggestions-products').hide();
                    $('#no-suggestions').show();
                    $suggestions.show();
                    return;
                }
                
                $('#no-suggestions').hide();
                
                // Render categories
                if (hasCategories) {
                    let html = '';
                    data.categories.forEach(function(cat) {
                        html += `<a href="{{ url('products') }}?category=${cat.slug}">
                            <i class="bi bi-folder"></i> ${highlightMatch(cat.name, query)}
                        </a>`;
                    });
                    $('#categories-list').html(html);
                    $('#suggestions-categories').show();
                } else {
                    $('#suggestions-categories').hide();
                }
                
                // Render brands
                if (hasBrands) {
                    let html = '';
                    data.brands.forEach(function(brand) {
                        html += `<a href="{{ url('products') }}?brand=${brand.slug}">
                            <i class="bi bi-tag"></i> ${highlightMatch(brand.name, query)}
                        </a>`;
                    });
                    $('#brands-list').html(html);
                    $('#suggestions-brands').show();
                } else {
                    $('#suggestions-brands').hide();
                }
                
                // Render products
                if (hasProducts) {
                    let html = '';
                    data.products.forEach(function(product) {
                        html += `<a href="{{ url('products') }}/${product.slug}" class="suggestion-product">
                            <img src="${product.image}" alt="${product.title}">
                            <div class="suggestion-product-info">
                                <div class="suggestion-product-title">${highlightMatch(product.title, query)}</div>
                                ${product.brand ? `<div class="suggestion-product-brand text-muted" style="font-size: 12px;">${product.brand}</div>` : ''}
                                <div class="suggestion-product-price">${product.price}</div>
                            </div>
                        </a>`;
                    });
                    $('#products-list').html(html);
                    $('#suggestions-products').show();
                } else {
                    $('#suggestions-products').hide();
                }
                
                $suggestions.show();
            }
            
            function highlightMatch(text, query) {
                const regex = new RegExp(`(${escapeRegex(query)})`, 'gi');
                return text.replace(regex, '<strong style="color: #dc3545;">$1</strong>');
            }
            
            function escapeRegex(string) {
                return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
            }
        });
    </script>

    <!-- Mobile Search Suggestions Script -->
    <script>
        $(document).ready(function() {
            var mobileSearchTimeout;
            
            // Use event delegation for dynamically loaded content
            $(document).on('input', '#mobile-search-input', function() {
                var $mobileInput = $(this);
                var $mobileSuggestions = $('#mobile-search-suggestions');
                var query = $mobileInput.val().trim();
                
                clearTimeout(mobileSearchTimeout);
                
                if (query.length < 2) {
                    $mobileSuggestions.hide();
                    return;
                }
                
                // Show loading
                $mobileSuggestions.html('<div class="mobile-search-loading"><i class="fas fa-spinner fa-spin"></i> Đang tìm...</div>').show();
                
                mobileSearchTimeout = setTimeout(function() {
                    $.ajax({
                        url: '{{ route("search.suggestions") }}',
                        data: { q: query },
                        dataType: 'json',
                        success: function(data) {
                            var html = '';
                            
                            // Categories
                            if (data.categories && data.categories.length > 0) {
                                html += '<div class="mobile-suggestions-section">';
                                html += '<div class="mobile-suggestions-title">Danh mục</div>';
                                html += '<div class="mobile-suggestions-list">';
                                data.categories.forEach(function(cat) {
                                    html += '<a href="{{ url("products") }}?category=' + cat.slug + '"><i class="fas fa-folder"></i> ' + cat.name + '</a>';
                                });
                                html += '</div></div>';
                            }
                            
                            // Brands
                            if (data.brands && data.brands.length > 0) {
                                html += '<div class="mobile-suggestions-section">';
                                html += '<div class="mobile-suggestions-title">Thương hiệu</div>';
                                html += '<div class="mobile-suggestions-list">';
                                data.brands.forEach(function(brand) {
                                    html += '<a href="{{ url("products") }}?brand=' + brand.slug + '"><i class="fas fa-tag"></i> ' + brand.name + '</a>';
                                });
                                html += '</div></div>';
                            }
                            
                            // Products
                            if (data.products && data.products.length > 0) {
                                html += '<div class="mobile-suggestions-section">';
                                html += '<div class="mobile-suggestions-title">Sản phẩm</div>';
                                data.products.forEach(function(product) {
                                    var imageUrl = product.image || '{{ asset("images/placeholder.svg") }}';
                                    html += '<a href="{{ url("products") }}/' + product.slug + '" class="mobile-suggestion-product">';
                                    html += '<img src="' + imageUrl + '" alt="' + product.title + '" onerror="this.src=\'{{ asset("images/placeholder.svg") }}\'">';
                                    html += '<div class="mobile-suggestion-product-info">';
                                    html += '<div class="name">' + product.title + '</div>';
                                    html += '<div class="price">' + product.price + '</div>';
                                    html += '</div></a>';
                                });
                                html += '</div>';
                            }
                            
                            if (!html) {
                                html = '<div class="mobile-search-loading">Không tìm thấy kết quả</div>';
                            }
                            
                            $mobileSuggestions.html(html).show();
                        },
                        error: function(xhr, status, error) {
                            console.error('Mobile search error:', error);
                            $mobileSuggestions.html('<div class="mobile-search-loading">Lỗi tìm kiếm</div>').show();
                        }
                    });
                }, 300);
            });
            
            // Hide when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('#mobile-search-input, #mobile-search-suggestions').length) {
                    $('#mobile-search-suggestions').hide();
                }
            });
            
            // Clear suggestions when offcanvas is hidden
            $(document).on('hidden.bs.offcanvas', '#offcanvasSearch', function() {
                $('#mobile-search-suggestions').hide();
                $('#mobile-search-input').val('');
            });
        });
    </script>

    @stack('scripts')
</body>

</html>