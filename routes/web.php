<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\FileController;

Route::get('/', function () {
    return auth()->check() 
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

require __DIR__ . '/web/auth.php';
require __DIR__ . '/web/user.php';
require __DIR__ . '/web/category.php';

Route::middleware(['auth:sanctum', 'web'])->group(function () {
    Route::view('/dashboard', 'dashboard.index')->name('dashboard');
});

