<?php

use App\Http\Controllers\Products\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/products/list', [ProductController::class, 'fetchProductsList']);

