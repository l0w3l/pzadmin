<?php

require __DIR__.'/versions/v1/entrypoint.php';

Route::any('/{any}', function () {
    return response(status: 404);
})->where('any', '.*');
