<?php

use App\Http\Controllers\Api\NotificationController;
use Illuminate\Support\Facades\Route;


Route::prefix('v1')->group(function () {
    Route::prefix('/notifications')->group(function () {
        Route::post('', [NotificationController::class, 'store']);
        Route::get('/{notification}', [NotificationController::class, 'show']);
        Route::get('/notifications', [NotificationController::class, 'index']);
    });
});
