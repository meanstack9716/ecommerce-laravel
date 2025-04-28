<?php

use App\Http\Controllers\User\RoleController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ValidateForm;

Route::prefix('users')->group(function () {
    Route::middleware(['auth:sanctum', 'web'])->group(function () {

        Route::middleware('is_admin')->group(function () {
            Route::get('/list', [UserController::class, 'fetchUserList'])->name('user.list');
        });
    });
});

Route::prefix('sellers')->group(function () {
    Route::middleware(['auth:sanctum', 'web'])->group(function () {

        Route::middleware('is_admin')->group(function () {
            Route::get('/list', [UserController::class, 'fetchSellerList'])->name('seller.list');

            Route::prefix('register')->group(function () {

                Route::get('/personal-details', [UserController::class, 'showRegistrationForm'])->name('seller.register.personal');
                Route::post('/personal-details', [UserController::class, 'storePersonalDetails'])
                    ->middleware('validateForm:addClientPersonalSchema')
                    ->name('seller.register.personal.submit');

                Route::get('/business', [UserController::class, 'showRegistrationForm'])
                    ->name('seller.register.business');
                Route::post('/business', [UserController::class, 'storeBusiness'])
                    ->middleware('validateForm:addClientBusinessSchema')
                    ->name('seller.register.business.submit');

                Route::get('/identity', [UserController::class, 'showRegistrationForm'])->name('seller.register.identity');
                Route::post('/identity', [UserController::class, 'storeIdentity'])->name('seller.register.identity.submit');

                Route::get('/complete', [UserController::class, 'showRegistrationForm'])->name('seller.register.complete');
                Route::post('/complete', [UserController::class, 'completeRegistration'])->name('seller.register.complete.submit');
            });
        });
    });
});