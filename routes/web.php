<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\AccountController;




Route::get('/', function () {
    return view('welcome');
});


Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/temp', [HomeController::class, 'temp'])->name('temp');

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
