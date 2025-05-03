<?php

use App\Http\Controllers\Products\ProductController;
use App\Http\Controllers\Products\ProductBrandController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ValidateRequest;

Route::middleware(['auth:sanctum', 'web'])->group(function () {
    Route::middleware('is_admin_or_client')->group(function () {

        Route::prefix('products')->group(function () {

            Route::get('/add/step1', [ProductController::class, 'showAddProductForm'])->name('products.add.step1');
            Route::post('/add/step1', [ProductController::class, 'storeProductCategoryDetails'])
                ->middleware('validateRequest:addNewProductCategorySchema')
                ->name('products.add.step1.submit');

            Route::get('/add/step2', [ProductController::class, 'showAddProductForm'])->name('products.add.step2');
            Route::post('/add/step2', [ProductController::class, 'storeProductCategoryDetails'])
                ->middleware('validateRequest:addNewProductCategorySchema')
                ->name('products.add.step2.submit');
        });

        Route::prefix('brands')->group(function () {

            Route::get('/add', [ProductBrandController::class, 'showAddProductBrandForm'])->name('products.brand.add');
            Route::post('/add', [ProductBrandController::class, 'showAddProductBrandForm'])
                ->middleware('validateRequest:addNewProductBrandSchema')
                ->name('products.brand.add.submit');

            Route::get('/list', [ProductBrandController::class, 'getAllProductBrandList'])->name('products.brand.list');
        });
    });
});