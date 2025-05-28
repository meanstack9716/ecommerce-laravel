<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SearchController;

Route::prefix('search')->middleware('auth:sanctum', 'web')->group(function () {

    Route::middleware('is_admin')->group(function () {
        Route::get('/sellers', [SearchController::class, 'searchSellers']);
        Route::get('/categories', [SearchController::class, 'searchCategories']);
        
    });
});