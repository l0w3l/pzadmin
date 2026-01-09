<?php

declare(strict_types=1);

namespace App\Services\Zomboid\Player;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\App;
use Lowel\LaravelServiceMaker\Services\ServiceFactoryInterface;

class PlayerServiceFactory implements ServiceFactoryInterface
{
    /**
     * @param  array<empty>  $params
     *
     * @throws BindingResolutionException
     */
    public function get(array $params = []): PlayerServiceInterface
    {
        return App::make(PlayerService::class);
    }
}
