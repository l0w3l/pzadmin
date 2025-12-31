<?php

declare(strict_types=1);

namespace App\Services\Steam;

use Lowel\LaravelServiceMaker\Services\ServiceFactoryInterface;

class SteamServiceFactory implements ServiceFactoryInterface
{
    function get(array $params = []): SteamServiceInterface {
        return new SteamService();
    }
}
