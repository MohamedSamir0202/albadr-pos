<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ApiJsonResponse;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ItemController;
use App\Http\Controllers\Api\V1\CartController;
use App\Http\Controllers\Api\V1\OrderController;

Route::middleware(ApiJsonResponse::class)->prefix('v1')->group(function () {

    // ======================
    // Auth
    // ======================
    Route::prefix('auth')->group(function () {
        Route::post('login',  [AuthController::class, 'login']);
        Route::post('signup', [AuthController::class, 'signup']);

        Route::middleware('auth:sanctum')->group(function () {
            Route::get('profile',  [AuthController::class, 'getProfile']);
            Route::post('profile', [AuthController::class, 'updateProfile']);
        });
    });

    // ======================
    // Items (Public)
    // ======================
    Route::get('items',      [ItemController::class, 'index']);
    Route::get('items/{id}', [ItemController::class, 'show']);

    // ======================
    // Protected Routes
    // ======================
    Route::middleware('auth:sanctum')->group(function () {

        // -------- Cart --------
        Route::get('cart',            [CartController::class, 'index']);
        Route::post('cart/add',           [CartController::class, 'add']);   // add item
        Route::put('cart/{id}',     [CartController::class, 'update']);  // update qty
        Route::delete('cart/{id}',  [CartController::class, 'destroy']); // remove item

        // -------- Checkout & Orders --------
    Route::post('/checkout', [OrderController::class, 'checkout'])->middleware('auth:api');
    Route::get('/orders', [OrderController::class, 'index'])->middleware('auth:api');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->middleware('auth:api');
    });

});
