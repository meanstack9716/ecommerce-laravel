<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ValidateRequest;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'registerUser'])->middleware('validateRequest:registerSchema');
    Route::post('/login', [AuthController::class, 'login'])->middleware('validateRequest:loginSchema');
    Route::post('/reset-password', [AuthController::class, 'resetUserPassword'])->middleware('validateRequest:resetPasswordSchema');
    Route::post('/send-email-code', [AuthController::class, 'sendEmailCode'])->middleware('validateRequest:isRegisteredEmailSchema');
    Route::post('/verify-email-code', [AuthController::class, 'verifyEmailCode'])->middleware('validateRequest:verifyEmailSchema');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});