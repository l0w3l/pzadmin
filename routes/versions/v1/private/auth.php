<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('/auth')->name('auth.')->group(function () {
    Route::get('/', [AuthController::class, 'index'])->name('index');

    Route::prefix('/tokens')->name('tokens.')->group(function () {
        Route::get('/ping', [AuthController::class, 'ping'])->name('ping');
        Route::get('/regenerate', [AuthController::class, 'regenerate'])->name('regenerate');
    });

    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});
