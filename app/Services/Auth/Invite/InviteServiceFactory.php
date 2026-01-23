<?php

declare(strict_types=1);

namespace App\Services\Auth\Invite;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\App;
use Lowel\LaravelServiceMaker\Services\ServiceFactoryInterface;

class InviteServiceFactory implements ServiceFactoryInterface
{
    /**
     * @param  array<empty>  $params
     *
     * @throws BindingResolutionException
     */
    public function get(array $params = []): InviteServiceInterface
    {
        return App::make(InviteService::class);
    }
}
