<?php

declare(strict_types=1);

namespace App\Repositories\Auth\Invite;

use Illuminate\Support\Facades\App;
use Lowel\LaravelServiceMaker\Repositories\RepositoryFactoryInterface;

class InviteRepositoryFactory implements RepositoryFactoryInterface
{
    public function get(): InviteRepositoryInterface
    {
        return App::make(InviteRepository::class);
    }
}
