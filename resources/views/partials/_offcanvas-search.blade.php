<div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1" id="offcanvasSearch" aria-labelledby="Search">
    <div class="offcanvas-header justify-content-center">
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="order-md-last">
            <h4 class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-primary">Tìm kiếm</span>
            </h4>
            <form role="search" action="{{ route('products.index') }}" method="get" class="d-flex mt-3 gap-0 position-relative" id="mobile-search-form">
                <input class="form-control rounded-start rounded-0 bg-light" type="text" 
                       name="search" id="mobile-search-input"
                       placeholder="Tìm kiếm sản phẩm..." 
                       aria-label="Tìm kiếm sản phẩm..."
                       autocomplete="off">
                <button class="btn btn-dark rounded-end rounded-0" type="submit">Tìm</button>
            </form>
            
            <!-- Mobile Search Suggestions -->
            <div id="mobile-search-suggestions" class="mobile-search-suggestions mt-2" style="display: none;"></div>
        </div>
    </div>
</div>

<style>
    .mobile-search-suggestions {
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 8px;
        max-height: 60vh;
        overflow-y: auto;
    }
    
    .mobile-suggestions-section {
        padding: 10px 0;
        border-bottom: 1px solid #eee;
    }
    
    .mobile-suggestions-section:last-child {
        border-bottom: none;
    }
    
    .mobile-suggestions-title {
        font-size: 12px;
        font-weight: 600;
        color: #666;
        padding: 5px 15px;
        text-transform: uppercase;
    }
    
    .mobile-suggestions-list a {
        display: block;
        padding: 10px 15px;
        color: #333;
        text-decoration: none;
        transition: background 0.2s;
    }
    
    .mobile-suggestions-list a:hover,
    .mobile-suggestions-list a:active {
        background: #f5f5f5;
    }
    
    .mobile-suggestions-list a i {
        margin-right: 10px;
        color: #666;
    }
    
    .mobile-suggestion-product {
        display: flex;
        align-items: center;
        padding: 10px 15px;
        border-bottom: 1px solid #f0f0f0;
        text-decoration: none;
        color: #333;
    }
    
    .mobile-suggestion-product:last-child {
        border-bottom: none;
    }
    
    .mobile-suggestion-product:hover,
    .mobile-suggestion-product:active {
        background: #f5f5f5;
    }
    
    .mobile-suggestion-product img {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 6px;
        margin-right: 12px;
    }
    
    .mobile-suggestion-product-info {
        flex: 1;
    }
    
    .mobile-suggestion-product-info .name {
        font-size: 14px;
        font-weight: 500;
        margin-bottom: 4px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .mobile-suggestion-product-info .price {
        font-size: 13px;
        color: #e74c3c;
        font-weight: 600;
    }
    
    .mobile-search-loading {
        text-align: center;
        padding: 20px;
        color: #666;
    }
</style>