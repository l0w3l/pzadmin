<?php

declare(strict_types=1);

namespace App\Repositories\Auth\Token;

use Illuminate\Support\Facades\App;
use Lowel\LaravelServiceMaker\Repositories\RepositoryFactoryInterface;

class TokenRepositoryFactory implements RepositoryFactoryInterface
{
    public function get(): TokenRepositoryInterface
    {
        return App::make(TokenRepository::class);
    }
}
