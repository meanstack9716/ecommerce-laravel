<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;

Route::prefix('admin')->middleware('auth:sanctum', 'web')->group(function () {
    Route::middleware('is_admin_or_client')->group(function () {
        Route::get('/sales/overview', [DashboardController::class, 'salesOverview']);
        Route::get('/sales/over-time', [DashboardController::class, 'salesOverTime']);
        Route::get('/sales/top-products', [DashboardController::class, 'topProducts']);
        Route::get('/orders/recent', [DashboardController::class, 'recentOrders']);
    });
});