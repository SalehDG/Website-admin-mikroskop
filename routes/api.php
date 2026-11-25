<?php

use App\Http\Controllers\Api\LicenseController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/licenses')->middleware('throttle:60,1')->group(function () {
    Route::post('/activate', [LicenseController::class, 'activate']);
    Route::post('/validate', [LicenseController::class, 'validateSerial']);
    Route::post('/deactivate', [LicenseController::class, 'deactivate']);
});
