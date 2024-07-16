<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => 'api',
    'prefix' => 'v1'
], function ($router) {
    Route::prefix('users')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
    });
    Route::prefix('products')->group(function () {
        Route::post('/save_product', [ProductController::class, 'createProduct'])->middleware('auth:api');
        Route::get('/{product}', [ProductController::class, 'show'])->middleware('auth:api');
    });
});
