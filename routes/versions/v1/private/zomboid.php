<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\Zomboid\LogsController;
use App\Http\Controllers\Api\V1\Zomboid\ZomboidController;
use Illuminate\Support\Facades\Route;

// Route::prefix('/zomboid')->name('zomboid.')->group(function () {
//
//    Route::prefix('/logs')->name('logs.')->withoutMiddleware('auth:sanctum')->group(function () {
//        Route::get('/console', [LogsController::class, 'console'])->name('console');
//    });
//
//    Route::get('/start', [ZomboidController::class, 'start'])->name('start');
//    Route::get('/down', [ZomboidController::class, 'down'])->name('down');
//    Route::get('/restart', [ZomboidController::class, 'restart'])->name('restart');
// });
