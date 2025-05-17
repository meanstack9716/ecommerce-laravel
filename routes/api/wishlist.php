<?php

use App\Http\Controllers\Products\ProductWishlistController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ValidateRequest;

Route::prefix('wishlist')->group(function () {

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('/add', [ProductWishlistController::class, 'addProductToWish'])->middleware('validateRequest:addProductCartSchema');
        Route::get('/list', [ProductWishlistController::class, 'fetchWishlist']);
        Route::delete('/remove', [ProductWishlistController::class, 'removeItemsFromWishList'])->middleware('validateRequest:removeWishlistSchema');
        Route::delete('/remove-all', [ProductWishlistController::class, 'removeAllWishlistItems']);
        Route::post('/to-cart', [ProductWishlistController::class, 'moveItemToCart'])->middleware('validateRequest:removeWishlistSchema');
    });
});