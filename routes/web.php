<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

// Route::get('/', function () {
//     return view('welcome');
//     // return view('home');
// });

Route::prefix('api/auth')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');
});

Route::view('/{any?}', 'welcome')->where('any', '.*');