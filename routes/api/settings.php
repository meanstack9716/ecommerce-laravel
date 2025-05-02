<?php

use App\Http\Controllers\Settings\SettingsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\ValidateRequest;

Route::prefix('settings')->group(function () {
    Route::get('/faq', [SettingsController::class, 'fetchFaqList']);
});