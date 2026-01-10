<?php

Route::prefix('/v1')->name('v1.')->group(function () {
    require __DIR__.'/versions/v1/index.php';
});

Route::any('/{any}', function () {
    return redirect()->route('welcome');
})->where('any', '.*');
