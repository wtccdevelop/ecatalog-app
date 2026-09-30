<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Admin\BrandController as AdminBrandController;
use App\Http\Controllers\Api\Admin\DashboardController as AdminDashboardController;

// Route::get('/', function () {
//     return view('welcome');
//     // return view('home');
// });

Route::prefix('api/auth')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');
});

// ---------- ADMIN (wajib login) ----------
Route::prefix('api/admin')->middleware('auth')->group(function () {
    Route::get('/dashboard', AdminDashboardController::class);
    Route::apiResource('brands', AdminBrandController::class)->except('show');
    // Tahap berikutnya ditambah di sini
});


Route::view('/{any?}', 'welcome')->where('any', '.*');