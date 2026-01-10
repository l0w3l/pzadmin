<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\Zomboid\LogsController;
use App\Http\Controllers\Api\V1\Zomboid\PlayersController;
use App\Http\Controllers\Api\V1\Zomboid\ZomboidController;
use Illuminate\Support\Facades\Route;

Route::prefix('/zomboid')->name('zomboid.')->group(function () {
    Route::get('/', [ZomboidController::class, 'index'])->name('index');

    Route::prefix('/players')->name('players.')->group(function () {
        Route::get('/', [PlayersController::class, 'index'])->name('index');
    });

    //    Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('/logs')->name('logs.')->group(function () {
        Route::get('/console', [LogsController::class, 'console'])->name('console');
        Route::get('/console/{leftRange}/{rightRange}', [LogsController::class, 'console_cursor'])->name('console.cursor');
    });

    Route::get('/start', [ZomboidController::class, 'start'])->name('start');
    Route::get('/down', [ZomboidController::class, 'down'])->name('down');
    Route::get('/restart', [ZomboidController::class, 'restart'])->name('restart');
    //    });
});
