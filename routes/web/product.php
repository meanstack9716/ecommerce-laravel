<?php

use App\Http\Controllers\Products\ProductController;
use App\Http\Controllers\Products\ProductBrandController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ValidateRequest;

Route::middleware(['auth:sanctum', 'web'])->group(function () {
    Route::middleware('is_admin_or_client')->group(function () {

        Route::prefix('products')->group(function () {

            Route::get('/list', [ProductController::class, 'getAllProductsList'])->name('products.list');

            Route::get('/list/{id}', [ProductController::class, 'getProductDetailView'])->name('products.details.show');

            Route::get('/add/step1', [ProductController::class, 'showAddProductForm'])->name('products.add.step1');
            Route::post('/add/step1', [ProductController::class, 'storeProductCategoryDetails'])
            ->middleware('validateRequest:addNewProductCategorySchema')
            ->name('products.add.step1.submit');
            
            Route::get('/add/step2', [ProductController::class, 'showAddProductForm'])->name('products.add.step2');
            Route::post('/add/step2', [ProductController::class, 'storeProductBasicDetails'])
            ->middleware('validateRequest:addNewProductDetailsSchema')
            ->name('products.add.step2.submit');
            
            Route::get('/add/step3', [ProductController::class, 'showAddProductForm'])->name('products.add.step3');
            Route::post('/add/step3', [ProductController::class, 'storeProductVariantDetails'])
            ->middleware('validateRequest:addProductVariantsSchema')
            ->name('products.add.step3.submit');
            
            Route::get('/add/step4', [ProductController::class, 'showAddProductForm'])->name('products.add.step4');
            Route::post('/add/step4', [ProductController::class, 'completeProductRegistration'])
            ->middleware('validateRequest:addProductGallerySchema')
            ->name('products.add.step4.submit');

            Route::get('/edit/{id}', [ProductController::class, 'getEditProductForm'])->name('product.edit.form');
            Route::post('/edit/{id}', [ProductController::class, 'updateProductDetails'])->name('product.edit.submit');
        });
        

        Route::prefix('brands')->group(function () {

            Route::get('/add', [ProductBrandController::class, 'showAddProductBrandForm'])->name('products.brand.add');
            Route::post('/add', [ProductBrandController::class, 'showAddProductBrandForm'])
                ->middleware('validateRequest:addNewProductBrandSchema')
                ->name('products.brand.add.submit');

            Route::get('/list', [ProductBrandController::class, 'getAllProductBrandList'])->name('products.brand.list');

            Route::get('/{brandId}/edit', [ProductBrandController::class, 'editBrandDetails'])->name('products.brand.edit');
            Route::put('/{brandId}', [ProductBrandController::class, 'updateBrand'])
            ->middleware('validateRequest:updateProductBrandSchema')
            ->name('products.brand.update');
        });
    });
});