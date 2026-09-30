<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Admin\BrandController as AdminBrandController;
use App\Http\Controllers\Api\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Api\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Api\Admin\ProductImageController as AdminProductImageController;
use App\Http\Controllers\Api\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Api\Admin\BannerController as AdminBannerController;

Route::prefix('api/auth')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');
});

// ---------- ADMIN (wajib login) ----------
Route::prefix('api/admin')->middleware('auth')->group(function () {
    Route::get('/dashboard', AdminDashboardController::class);

    Route::apiResource('brands', AdminBrandController::class)->except('show');

    // Produk
    Route::patch('products/{product}/toggle', [AdminProductController::class, 'toggle']);
    Route::apiResource('products', AdminProductController::class);
    Route::post('products/{product}/images', [AdminProductImageController::class, 'store']);
    Route::delete('products/{product}/images/{image}', [AdminProductImageController::class, 'destroy']);

    // Ulasan
    Route::patch('reviews/{review}', [AdminReviewController::class, 'toggle']);
    Route::delete('reviews/{review}', [AdminReviewController::class, 'destroy']);

    // Banner
    Route::patch('banners/{banner}/toggle', [AdminBannerController::class, 'toggle']);
    Route::apiResource('banners', AdminBannerController::class)->except('show');
});

Route::view('/{any?}', 'welcome')->where('any', '.*');