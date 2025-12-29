<?php

use Illuminate\Support\Facades\Route;

Route::any('/{any}', \App\Http\Controllers\WelcomeController::class)->where('any', '.*');
