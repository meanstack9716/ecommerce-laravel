<?php

use App\Http\Controllers\Products\ProductController;
use App\Http\Controllers\Orders\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ValidateRequest;


Route::prefix('products')->group(function () {

    Route::get('/list', [ProductController::class, 'fetchProductsList']);
    Route::get('/colors-list', [ProductController::class, 'fetchProductsColorsList']);
    Route::get('/{id}', [ProductController::class, 'fetchProductDetailsById']);
    Route::get('/{id}/similar', [ProductController::class, 'fetchSimilarProducts']);
    Route::get('/{id}/reviews', [ProductController::class, 'fetchProductReviews']);
    Route::middleware(['auth:sanctum'])->group(function () {
        
        Route::get('/{id}/user-review', [ProductController::class, 'fetchUserProductReview']);
        Route::post('/review', [OrderController::class, 'createProductReview'])->middleware('validateRequest:postNewReviewSchema');
        Route::post('/update-review', [OrderController::class, 'updateProductReview'])->middleware('validateRequest:postNewReviewSchema');
    });
});