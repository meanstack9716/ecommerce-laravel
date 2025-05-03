<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\PanelAuthController;
use App\Http\Middleware\ValidateRequest;

Route::middleware(['guest'])->group(function () {
    Route::view('/login', 'auth.login')->name('login');
    Route::view('/forgot-password', 'auth.forgot-password')->name('forgot-password');
    Route::get('/verify-reset-code', [PanelAuthController::class, 'showVerifyCodeForm'])->name('password.verify-code');
    Route::get('/reset-password', [PanelAuthController::class, 'showResetPasswordForm'])->name('password.reset');
});

Route::post('/login', [PanelAuthController::class, 'signinPanelUser'])->middleware('validateRequest:loginSchema')->name('login.submit');
Route::post('/forgot-password', [PanelAuthController::class, 'sendEmailCodeForUser'])->middleware('validateRequest:isRegisteredEmailSchema')->name('password.email');
Route::post('/resend-verification-code', [PanelAuthController::class, 'resendVerificationCode'])->name('password.resend-code');
Route::post('/verify-reset-code', [PanelAuthController::class, 'verifyEmailCode'])->middleware('validateRequest:verifyEmailSchema')->name('password.submit-code');
Route::post('/reset-password', [PanelAuthController::class, 'resetUserPassword'])->middleware('validateRequest:resetPasswordSchema')->name('password.reset');
Route::post('/logout', [PanelAuthController::class, 'logoutCurrentUser'])->name('logout');

