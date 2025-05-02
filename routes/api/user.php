<?php

use App\Http\Controllers\User\RoleController;
use App\Http\Controllers\User\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ValidateRequest;

Route::prefix('user')->group(function () {
    Route::get('/roles', [RoleController::class, 'fetchRolesList']);

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('/me', [UserController::class, 'fetchCurrentUser']);
        Route::put('/update-profile', [UserController::class, 'updateUserDetails'])->middleware('validateRequest:updateUserSchema');
        Route::post('/update-profile-pic', [UserController::class, 'updateProfilePic'])->middleware('validateRequest:imageSchema');
        Route::delete('/delete-profile-pic', [UserController::class, 'deleteProfilePic']);
    });
});