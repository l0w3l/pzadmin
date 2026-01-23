<?php

use Illuminate\Support\Facades\Route;

Route::get('/', \App\Http\Controllers\WelcomeController::class)->name('welcome');

Route::any('/{any}', \App\Http\Controllers\WelcomeController::class)->where('any', '.*');
