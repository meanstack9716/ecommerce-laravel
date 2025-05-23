<?php

use App\Http\Controllers\Admin\PromoCodeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ValidateRequest;

Route::prefix('promo-code')->group(function () {
    Route::middleware(['auth:sanctum', 'web'])->group(function () {

        Route::middleware('is_admin')->group(function () {
            
            Route::get('/list', [PromoCodeController::class, 'getAllPromoCodeList'])->name('promo-code.list');

            Route::get('/add', [PromoCodeController::class, 'showAddNewPromoCodeForm'])->name('promo-code.add');
            Route::post('/add', [PromoCodeController::class, 'createNewPromoCodeForm'])
                ->middleware('validateRequest:addPromoCodeSchema')
                ->name('promo-code.add.submit');

            Route::get('/{codeId}/edit', [PromoCodeController::class, 'editPromoCodeDetails'])->name('promo-code.edit');
            Route::put('/{codeId}', [PromoCodeController::class, 'updatePromoCode'])
                ->middleware('validateRequest:updatePromoCodeSchema')
                ->name('promo-code.update');
        });
    });
});