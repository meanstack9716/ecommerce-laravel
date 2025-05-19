<?php

use App\Http\Controllers\Products\ProductController;
use App\Http\Controllers\Orders\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ValidateRequest;


Route::prefix('products')->group(function () {

    Route::get('/list', [ProductController::class, 'fetchProductsList']);
    Route::get('/{id}', [ProductController::class, 'fetchProductDetailsById']);
    Route::middleware(['auth:sanctum'])->group(function () {

        Route::post('/review', [OrderController::class, 'createProductReview'])->middleware('validateRequest:postNewReviewSchema');
    });
});