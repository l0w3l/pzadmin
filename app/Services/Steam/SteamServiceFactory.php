<?php

declare(strict_types=1);

namespace App\Services\Steam;

use App\Exceptions\Services\Steam\SteamKeyNotFoundException;
use Lowel\LaravelServiceMaker\Services\ServiceFactoryInterface;

class SteamServiceFactory implements ServiceFactoryInterface
{
    /**
     * @param array{
     *   'steam_api_key'?: string,
     * }|array<empty> $params
     *
     * @throws SteamKeyNotFoundException
     */
    public function get(array $params = []): SteamServiceInterface
    {
        return new SteamProxyService($params['steam_api_key'] ?? config('steam.key') ?? throw new SteamKeyNotFoundException);
    }
}
