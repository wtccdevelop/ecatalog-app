<?php

use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SiteController;
use Illuminate\Support\Facades\Route;

Route::get('/site', SiteController::class);
Route::get('/home', HomeController::class);
Route::get('/products/search', [ProductController::class, 'search']); // harus sebelum {slug}
Route::get('products', [ProductController::class, 'index']);

Route::get('/products/{slug}', [ProductController::class, 'show']);