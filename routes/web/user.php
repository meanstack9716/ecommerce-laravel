<?php

use App\Http\Controllers\User\RoleController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ValidateRequest;

Route::prefix('users')->group(function () {
    Route::middleware(['auth:sanctum', 'web'])->group(function () {

        Route::middleware('is_admin')->group(function () {
            Route::get('/list', [UserController::class, 'fetchUserList'])->name('user.list');

            Route::get('/create', [UserController::class, 'showAddUserForm'])->name('user.create');
            Route::post('/create', [UserController::class, 'createNewUser'])
                ->middleware('validateRequest:addNewUserSchema')
                ->name('user.create.submit');

            Route::get('/{userId}/edit', [UserController::class, 'editUserDetailsForm'])->name('user.edit');
            Route::put('/{userId}', [UserController::class, 'updateUserDetails'])
                ->middleware('validateRequest:updateUserDetailsSchema')
                ->name('user.update');

        });
    });
});

Route::prefix('sellers')->group(function () {
    Route::middleware(['auth:sanctum', 'web'])->group(function () {

        Route::middleware('is_admin')->group(function () {
            Route::get('/list', [UserController::class, 'fetchSellerList'])->name('seller.list');

            Route::get('/{sellerId}/edit', [UserController::class, 'editSellerDetailsForm'])->name('seller.edit');
            Route::put('/{sellerId}', [UserController::class, 'updateSellerDetails'])
                ->name('seller.update');

            Route::prefix('register')->group(function () {

                Route::get('/personal-details', [UserController::class, 'showRegistrationForm'])->name('seller.register.personal');
                Route::post('/personal-details', [UserController::class, 'storePersonalDetails'])
                    ->middleware('validateRequest:addClientPersonalSchema')
                    ->name('seller.register.personal.submit');

                Route::get('/business', [UserController::class, 'showRegistrationForm'])
                    ->name('seller.register.business');
                Route::post('/business', [UserController::class, 'storeBusiness'])
                    ->middleware('validateRequest:addClientBusinessSchema')
                    ->name('seller.register.business.submit');

                Route::get('/identity', [UserController::class, 'showRegistrationForm'])->name('seller.register.identity');
                Route::post('/identity', [UserController::class, 'storeIdentity'])
                    ->middleware('validateRequest:addClientIdentitySchema')
                    ->name('seller.register.identity.submit');

                Route::get('/complete', [UserController::class, 'showRegistrationForm'])->name('seller.register.complete');
                Route::post('/complete', [UserController::class, 'completeRegistration'])->name('seller.register.complete.submit');
            });
        });
    });
});