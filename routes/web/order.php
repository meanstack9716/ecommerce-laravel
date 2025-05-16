<?php

use App\Http\Controllers\Orders\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ValidateRequest;

Route::middleware(['auth:sanctum', 'web'])->group(function () {
    Route::middleware('is_admin_or_client')->group(function () {

        Route::prefix('orders')->group(function () {

            Route::get('/list', [OrderController::class, 'getAllOrdersList'])->name('orders.list');
            Route::patch('/{orderId}/update-status', [OrderController::class, 'updateOrderStatus'])->name('orders.update-status');
            Route::get('/{orderId}/details', [OrderController::class, 'getOrderDetails'])->name('orders.details.show');

        });
    });
});