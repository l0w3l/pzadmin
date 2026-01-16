<?php

declare(strict_types=1);

namespace App\Services\Steam;

use Lowel\LaravelServiceMaker\Services\ServiceFactoryInterface;

class SteamServiceFactory implements ServiceFactoryInterface
{
    /**
     * @param  array<empty>  $params
     */
    public function get(array $params = []): SteamServiceInterface
    {
        return new SteamProxyService;
    }
}
