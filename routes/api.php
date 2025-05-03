<?php

use App\Http\Controllers\Products\ProductController;
use Illuminate\Support\Facades\Route;

require __DIR__ . '/api/auth.php';
require __DIR__ . '/api/user.php';
require __DIR__ . '/api/settings.php';
require __DIR__ . '/api/category.php';

Route::get('/product', [ProductController::class, 'fetchAllProducts']);