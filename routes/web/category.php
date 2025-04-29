<?php

use App\Http\Controllers\Admin\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ValidateForm;

Route::prefix('category')->group(function () {
    Route::middleware(['auth:sanctum', 'web'])->group(function () {

        Route::middleware('is_admin')->group(function () {

            Route::get('/list', [CategoryController::class, 'getAllCategoriesList'])->name('category.list');
            
            Route::get('/add', [CategoryController::class, 'showAddCategoryForm'])->name('category.add');
            Route::post('/add', [CategoryController::class, 'addNewCategory'])
            ->middleware('validateForm:addNewCategorySchema')
            ->name('category.add.submit');
            
            Route::get('/sub/list', [CategoryController::class, 'getAllSubCategoriesList'])->name('sub-category.list');
            Route::get('/sub/add', [CategoryController::class, 'showSubCategoryForm'])->name('sub-category.add');
            Route::post('/sub/add', [CategoryController::class, 'addNewSubCategory'])
            ->middleware('validateForm:addSubCategorySchema')
            ->name('sub-category.add.submit');
            
            Route::get('/get-subcategories', [CategoryController::class, 'getSubcategories'])->name('get.subcategories');
            Route::get('/product-type/list', [CategoryController::class, 'getAllProductTypesList'])->name('product-type.list');
            Route::get('/product-type/add', [CategoryController::class, 'showProductTypeForm'])->name('product-type.add');
            Route::post('/product-type/add', [CategoryController::class, 'addNewProductType'])
            ->middleware('validateForm:addProductTypeSchema')
            ->name('product-type.add.submit');
        });
    });
});