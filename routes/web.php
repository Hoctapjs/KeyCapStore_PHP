<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SearchController;

// Admin Controllers
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\BrandController as AdminBrandController;
use App\Http\Controllers\Admin\InventoryController as AdminInventoryController;
use App\Http\Controllers\Admin\ProductVariantController as AdminProductVariantController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\CouponController as AdminCouponController;
use App\Http\Controllers\Admin\TagController as AdminTagController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ReviewController;

// ChatController
use App\Http\Controllers\ChatController;


Route::get('/', [ProductController::class, 'index'])->name('home');

// Search suggestions API
Route::get('/search/suggestions', [SearchController::class, 'suggestions'])->name('search.suggestions');
Route::get('/search/brand/{slug}', [SearchController::class, 'productsByBrand'])->name('search.brand');
Route::get('/search/category/{slug}', [SearchController::class, 'productsByCategory'])->name('search.category');

Route::get('/temp', [HomeController::class, 'temp'])->name('temp');

// Test route to check password
Route::get('/test-login', function () {
    $user = \App\Models\User::where('email', 'admin@test.com')->first();
    if (!$user) {
        return 'User not found!';
    }

    $password = '12345678';
    $check = \Illuminate\Support\Facades\Hash::check($password, $user->password);

    return [
        'user_found' => true,
        'email' => $user->email,
        'name' => $user->name,
        'role' => $user->role,
        'password_check' => $check ? 'MATCH ✅' : 'NOT MATCH ❌',
        'hash_prefix' => substr($user->password, 0, 30) . '...'
    ];
});

// Auth
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/forgot-password', [AuthController::class, 'showForgotForm'])
    ->middleware('guest')
    ->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])
    ->middleware('guest')
    ->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])
    ->middleware('guest')
    ->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])
    ->middleware('guest')
    ->name('password.update');

Route::resource('addresses', AddressController::class);

// account
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::resource('addresses', AddressController::class);

    // --- CÁC ROUTE CHO TÀI KHOẢN (THÊM VÀO ĐÂY) ---
    Route::get('/account/profile', [AccountController::class, 'profile'])
        ->name('account.profile');

    Route::post('/account/profile', [AccountController::class, 'updateProfile'])
        ->name('account.updateProfile');

    Route::get('/account/password', [AccountController::class, 'password'])
        ->name('account.password');

    Route::post('/account/password', [AccountController::class, 'updatePassword'])
        ->name('account.updatePassword');
});

// ============ MODULE 2: CATALOG (PRODUCTS) ============

// Client - Product Routes
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');

// Client - Category Routes
Route::get('/categories/{slug}', [CategoryController::class, 'show'])->name('categories.show');

// Admin Routes - Product Management
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin,staff'])->group(function () {

    // Dashboard - both admin and staff
    Route::get('/dashboard', function () {
        $totalProducts = \App\Models\Product::count();
        $totalCategories = \App\Models\Category::count();
        $totalBrands = \App\Models\Brand::count();
        $lowStockProducts = \App\Models\Product::where('stock', '<', 10)->count();

        return view('admin.dashboard', compact('totalProducts', 'totalCategories', 'totalBrands', 'lowStockProducts'));
    })->name('dashboard');

    // Products - both admin and staff
    Route::resource('products', AdminProductController::class);
    Route::delete('products/images/{image}', [AdminProductController::class, 'deleteImage'])->name('products.images.delete');

    // Product Variants (nested resource) - both admin and staff
    Route::prefix('products/{product}/variants')->name('products.variants.')->group(function () {
        Route::get('/', [AdminProductVariantController::class, 'index'])->name('index');
        Route::get('/create', [AdminProductVariantController::class, 'create'])->name('create');
        Route::post('/', [AdminProductVariantController::class, 'store'])->name('store');
        Route::get('/{variant}/edit', [AdminProductVariantController::class, 'edit'])->name('edit');
        Route::put('/{variant}', [AdminProductVariantController::class, 'update'])->name('update');
        Route::delete('/{variant}', [AdminProductVariantController::class, 'destroy'])->name('destroy');
    });

    // Inventory Management - both admin and staff
    Route::get('inventory', [AdminInventoryController::class, 'index'])->name('inventory.index');
    Route::post('inventory/adjust-variant', [AdminInventoryController::class, 'adjust'])->name('inventory.adjust-variant');
    Route::get('inventory/{product}', [AdminInventoryController::class, 'show'])->name('inventory.show');
    Route::post('inventory/{product}/update-stock', [AdminInventoryController::class, 'updateStock'])->name('inventory.update-stock');
    Route::post('inventory/variant/{variant}/update-stock', [AdminInventoryController::class, 'updateVariantStock'])->name('inventory.update-variant-stock');

    // Orders Management - both admin and staff
    Route::resource('orders', AdminOrderController::class);
    Route::patch('orders/{order}/update-status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
});

// Admin Only Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    // Categories - admin only
    Route::resource('categories', AdminCategoryController::class);

    // Brands - admin only
    Route::resource('brands', AdminBrandController::class);

    // Reviews Management - admin only
    Route::get('reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::patch('reviews/{review}/approve', [AdminReviewController::class, 'approve'])->name('reviews.approve');
    Route::patch('reviews/{review}/reject', [AdminReviewController::class, 'reject'])->name('reviews.reject');
    Route::delete('reviews/{review}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');

    // Coupons Management - admin only
    Route::resource('coupons', AdminCouponController::class);

    // Tags Management - admin only
    Route::resource('tags', AdminTagController::class);
});

// Route cho Cart (chức năng liên quan đến giỏ hàng)
// Route::get('/cart', [CartController::class, 'index'])->name('cart.index');

// Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');

// Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');

// Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

// // Route cho AJAX
// Route::get('/cart/ajax/info', [CartController::class, 'ajaxCartInfo'])
//     ->name('cart.ajax.info');

// Cart
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

// Ajax cart info
Route::get('/cart/ajax/info', [CartController::class, 'ajaxCartInfo'])->name('cart.ajax.info');

Route::get('/cart/ajax/table', [CartController::class, 'ajaxTable']);

// Wishlist routes
Route::middleware('auth')->group(function () {
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/toggle/{product}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::delete('/wishlist/remove/{product}', [WishlistController::class, 'remove'])->name('wishlist.remove');
});
Route::get('/wishlist/count', [WishlistController::class, 'count'])->name('wishlist.count');

// Checkout
Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'placeOrder'])->name('checkout.place');
    Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');
});

// Payment
Route::get('/payment/vnpay/return', [CheckoutController::class, 'vnpayReturn'])
    ->name('payment.vnpay.return');

// Review routes
Route::middleware('auth')->group(function () {
    Route::get('/products/{product}/review', [ReviewController::class, 'create'])->name('review.create');
    Route::post('/products/{product}/review', [ReviewController::class, 'store'])->name('review.store');
    Route::get('/reviews/{review}/edit', [ReviewController::class, 'edit'])->name('review.edit');
    Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('review.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('review.destroy');
});

// Chat
Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
Route::post('/chat/send', [ChatController::class, 'sendMessage']);

// About, Contect
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'submit'])->name('contact.submit');
