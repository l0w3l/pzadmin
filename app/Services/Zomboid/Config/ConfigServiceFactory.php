<?php

declare(strict_types=1);

namespace App\Services\Zomboid\Config;

use Lowel\LaravelServiceMaker\Services\ServiceFactoryInterface;

class ConfigServiceFactory implements ServiceFactoryInterface
{
    /**
     * @param array{
     *     zomboidPath?: string,
     *     serverName?: string
     * }|array<empty> $params
     */
    public function get(array $params = []): ConfigServiceInterface
    {
        return new ConfigService(
            $params['zomboidPath'] ?? base_path('/docker/zomboid/storage/data/Server/'),
            $params['serverName'] ?? config('app.name'),
        );
    }
}
