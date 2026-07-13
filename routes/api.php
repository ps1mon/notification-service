<?php

use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\ReportController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('notifications')->group(function () {
        Route::post('/', [NotificationController::class, 'store']);
        Route::get('/', [NotificationController::class, 'index']);
        Route::get('/{notification}', [NotificationController::class, 'show']);
    });

    Route::prefix('reports')->group(function () {
        Route::post('/', [ReportController::class, 'store']);
        Route::get('/{report}', [ReportController::class, 'show']);
        Route::get('/{report}/download', [ReportController::class, 'download']);
    });
});
