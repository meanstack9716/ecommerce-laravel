<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

Route::get('/', function () {
    return auth()->check() 
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

require __DIR__ . '/web/auth.php';
require __DIR__ . '/web/user.php';

Route::middleware(['auth:sanctum', 'web'])->group(function () {
    Route::view('/dashboard', 'dashboard.index')->name('dashboard');
});

