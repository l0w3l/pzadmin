<?php

declare(strict_types=1);

namespace App\Repositories\Auth\User;

use Illuminate\Support\Facades\App;
use Lowel\LaravelServiceMaker\Repositories\RepositoryFactoryInterface;

class UserRepositoryFactory implements RepositoryFactoryInterface
{
    public function get(): UserRepositoryInterface
    {
        return App::make(UserRepository::class);
    }
}
