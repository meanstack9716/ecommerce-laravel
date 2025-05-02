<?php

use App\Http\Controllers\Admin\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ValidateRequest;

Route::prefix('category')->group(function () {
    Route::middleware(['auth:sanctum', 'web'])->group(function () {

        Route::get('/get-subcategories', [CategoryController::class, 'getSubcategories'])->name('get.subcategories');
        Route::get('/get-subSubCategory', [CategoryController::class, 'getSubSubcategories'])->name('get.subSubCategories');

        Route::middleware('is_admin')->group(function () {

            Route::get('/list', [CategoryController::class, 'getAllCategoriesList'])->name('category.list');
            
            Route::get('/add', [CategoryController::class, 'showAddCategoryForm'])->name('category.add');
            Route::post('/add', [CategoryController::class, 'addNewCategory'])
            ->middleware('validateRequest:addNewCategorySchema')
            ->name('category.add.submit');
            
            Route::get('/sub/list', [CategoryController::class, 'getAllSubCategoriesList'])->name('sub-category.list');
            Route::get('/sub/add', [CategoryController::class, 'showSubCategoryForm'])->name('sub-category.add');
            Route::post('/sub/add', [CategoryController::class, 'addNewSubCategory'])
            ->middleware('validateRequest:addSubCategorySchema')
            ->name('sub-category.add.submit');
            
            Route::get('/sub-sub/list', [CategoryController::class, 'getAllSubSubCategoryList'])->name('sub-sub-category.list');
            Route::get('/sub-sub/add', [CategoryController::class, 'showSubSubCategoryForm'])->name('sub-sub-category.add');
            Route::post('/sub-sub/add', [CategoryController::class, 'addNewSubSubCategory'])
            ->middleware('validateRequest:addSubSubCategorySchema')
            ->name('sub-sub-category.add.submit');
        });
    });
});