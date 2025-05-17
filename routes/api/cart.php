<?php

use App\Http\Controllers\Products\ProductCartController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ValidateRequest;

Route::prefix('cart')->group(function () {

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('/add', [ProductCartController::class, 'addProductToCart'])->middleware('validateRequest:addProductCartSchema');
        Route::get('/list', [ProductCartController::class, 'fetchCartItemList']);
        Route::delete('/remove', [ProductCartController::class, 'removeCartItems'])->middleware('validateRequest:removeProductCartSchema');
        Route::delete('/remove-all', [ProductCartController::class, 'removeAllCartItems']);
        Route::put('/update', [ProductCartController::class, 'updateCartProduct'])->middleware('validateRequest:updateProductCartSchema');
        Route::post('/to-wishlist', [ProductCartController::class, 'moveItemToWishlist'])->middleware('validateRequest:removeProductCartSchema');
    });
});