<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;

// Admin Controllers
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\BrandController as AdminBrandController;
use App\Http\Controllers\Admin\InventoryController as AdminInventoryController;




Route::get('/', function () {
    return view('welcome');
});


Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/temp', [HomeController::class, 'temp'])->name('temp');

// Test route to check password
Route::get('/test-login', function() {
    $user = \App\Models\User::where('email', 'admin@test.com')->first();
    if(!$user) {
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
    
    // Dashboard
    Route::get('/dashboard', function() {
        $totalProducts = \App\Models\Product::count();
        $totalCategories = \App\Models\Category::count();
        $totalBrands = \App\Models\Brand::count();
        $lowStockProducts = \App\Models\Product::where('stock', '<', 10)->count();
        
        return view('admin.dashboard', compact('totalProducts', 'totalCategories', 'totalBrands', 'lowStockProducts'));
    })->name('dashboard');
    
    // Products
    Route::resource('products', AdminProductController::class);
    
    // Categories
    Route::resource('categories', AdminCategoryController::class);
    
    // Brands
    Route::resource('brands', AdminBrandController::class);
    
    // Inventory Management
    Route::get('inventory', [AdminInventoryController::class, 'index'])->name('inventory.index');
    Route::post('inventory/adjust', [AdminInventoryController::class, 'adjust'])->name('inventory.adjust');
    Route::get('inventory/{product}', [AdminInventoryController::class, 'show'])->name('inventory.show');
    Route::post('inventory/{product}/update-stock', [AdminInventoryController::class, 'updateStock'])->name('inventory.update-stock');
    Route::post('inventory/variant/{variant}/update-stock', [AdminInventoryController::class, 'updateVariantStock'])->name('inventory.update-variant-stock');
});
