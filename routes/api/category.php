<?php

use App\Http\Controllers\Admin\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/categories/list', [CategoryController::class, 'fetchCategoryList']);
Route::get('/categories/list/{id}', [CategoryController::class, 'fetchCategoryById']);

Route::get('/sub-categories/list', [CategoryController::class, 'fetchSubCategoryList']);
Route::get('/sub-categories/list/{id}', [CategoryController::class, 'fetchSubCategoryById']);

Route::get('/sub-sub-categories/list', [CategoryController::class, 'fetchSubSubCategoryList']);
Route::get('/sub-sub-categories/list/{id}', [CategoryController::class, 'fetchSubSubCategoryById']);

