<?php

use App\Http\Controllers\Admin\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ValidateForm;

Route::prefix('category')->group(function () {
    Route::middleware(['auth:sanctum', 'web'])->group(function () {

        Route::middleware('is_admin')->group(function () {
            Route::get('/add', [CategoryController::class, 'showAddCategoryForm'])->name('category.add');
            Route::post('/add', [CategoryController::class, 'showAddCategoryForm'])
                ->middleware('validateForm:addNewCategorySchema')
                ->name('category.add.submit');
        });
    });
});