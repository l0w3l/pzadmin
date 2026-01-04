<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\Auth\VerifyController;
use Illuminate\Support\Facades\Route;

Route::prefix('/auth')->name('auth.')->group(function () {
    Route::prefix('/verify')->name('verify.')->group(function () {
        Route::get('/hash/{hash}', [VerifyController::class, 'hash'])->name('hash');
        Route::get('/username/{username}', [VerifyController::class, 'username'])->name('username');
        Route::get('/email/{email}', [VerifyController::class, 'email'])->name('email');
    });

    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/registration', [AuthController::class, 'registration'])->name('registration');
});
