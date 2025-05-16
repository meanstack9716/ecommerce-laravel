<?php

use App\Http\Controllers\User\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ValidateRequest;

Route::prefix('address')->group(function () {
    Route::middleware(['auth:sanctum'])->group(function () {

        Route::get('/types-list', [UserController::class, 'fetchAddressTypeList']);
        Route::get('/list', [UserController::class, 'fetchUserAddressList']);
        Route::post('/add', [UserController::class, 'addNewAddress'])->middleware('validateRequest:addNewAddressSchema');
        Route::put('/update', [UserController::class, 'updateUserAddress'])->middleware('validateRequest:updateAddressSchema');
        Route::delete('/remove/{id}', [UserController::class, 'deleteAddress']);
    });
});