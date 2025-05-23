<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\FileController;
use App\Http\Controllers\Admin\DashboardController;

Route::get('/', function () {
    return auth()->check() 
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

require __DIR__ . '/web/auth.php';
require __DIR__ . '/web/user.php';
require __DIR__ . '/web/category.php';
require __DIR__ . '/web/product.php';
require __DIR__ . '/web/order.php';
require __DIR__ . '/web/promoCode.php';

Route::middleware(['auth:sanctum', 'web'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'viewDashboard'])->name('dashboard');
});

