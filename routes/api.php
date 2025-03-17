<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Fenixthelord\License\Http\Controllers\LicenseController;


if (config('laravel-license.mode') === 'server') {

    Route::prefix('licenses')->group(function () {
        Route::post('/create', [LicenseController::class, 'store']);
        Route::get('/{license}', [LicenseController::class, 'show']);
        Route::delete('/{license}', [LicenseController::class, 'destroy']);
        Route::post('/generate', [LicenseController::class, 'generate']);
        Route::post('/verify', [LicenseController::class, 'verify']);
    });
}
