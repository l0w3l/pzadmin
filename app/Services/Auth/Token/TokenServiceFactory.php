<?php

declare(strict_types=1);

namespace App\Services\Auth\Token;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\App;
use Lowel\LaravelServiceMaker\Services\ServiceFactoryInterface;

class TokenServiceFactory implements ServiceFactoryInterface
{
    /**
     * @param  array<empty>  $params
     *
     * @throws BindingResolutionException
     */
    public function get(array $params = []): TokenServiceInterface
    {
        return App::make(TokenService::class);
    }
}
