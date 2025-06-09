<?php

use App\Http\Controllers\Orders\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ValidateRequest;

Route::prefix('orders')->group(function () {
    Route::get('/status-types', [OrderController::class, 'fetchOrderStatusesList']);
    Route::get('/payment-types', [OrderController::class, 'fetchAvailablePaymentTypes']);

    Route::get('/razorpay/payment/callback', [OrderController::class, 'handleRazorpayCallback'])->name('razorpay.payment.callback');
    Route::middleware(['auth:sanctum'])->group(function () {

        Route::post('/new', [OrderController::class, 'createNewOrder'])->middleware('validateRequest:createNewOrderSchema');
        Route::get('/list', [OrderController::class, 'fetchAllOrderItems']);
        Route::get('/{orderId}', [OrderController::class, 'fetchOrderDetailsById']);

        Route::post('/validate-promo-code', [OrderController::class, 'validatePromoCode'])->middleware('validateRequest:validPromocodeSchema');;
    });
});