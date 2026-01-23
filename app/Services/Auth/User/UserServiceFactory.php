<?php

declare(strict_types=1);

namespace App\Services\Auth\User;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\App;
use Lowel\LaravelServiceMaker\Services\ServiceFactoryInterface;

class UserServiceFactory implements ServiceFactoryInterface
{
    /**
     * @param  array<empty>  $params
     *
     * @throws BindingResolutionException
     */
    public function get(array $params = []): UserServiceInterface
    {
        return App::make(UserService::class);
    }
}
