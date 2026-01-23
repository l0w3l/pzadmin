<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\Auth\VerifyController;

Route::prefix('/auth')->name('auth.')->group(function () {
    Route::prefix('/verify')->name('verify.')->group(function () {
        Route::get('/hash/{hash}', [VerifyController::class, 'hash'])->name('hash');
        Route::get('/username/{username}', [VerifyController::class, 'username'])->name('username');
        Route::get('/email/{email}', [VerifyController::class, 'email'])->name('email');
    });

    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/registration', [AuthController::class, 'registration'])->name('registration');

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/', [AuthController::class, 'index'])->name('index');

        Route::prefix('/tokens')->name('tokens.')->group(function () {
            Route::get('/ping', [AuthController::class, 'ping'])->name('ping');
            Route::get('/regenerate', [AuthController::class, 'regenerate'])->name('regenerate');
        });

        Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    });

});
