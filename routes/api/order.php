<?php

use App\Http\Controllers\Orders\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ValidateRequest;

Route::prefix('orders')->group(function () {
    Route::get('/status-types', [OrderController::class, 'fetchOrderStatusesList']);
    Route::middleware(['auth:sanctum'])->group(function () {

        Route::post('/new', [OrderController::class, 'createNewOrder'])->middleware('validateRequest:createNewOrderSchema');
        Route::get('/list', [OrderController::class, 'fetchAllOrderItems']);
        Route::get('/{orderId}', [OrderController::class, 'fetchOrderDetailsById']);

        Route::post('/validate-promo-code', [OrderController::class, 'validatePromoCode'])->middleware('validateRequest:validPromocodeSchema');;
    });
});