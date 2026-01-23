<?php

declare(strict_types=1);

namespace App\Services\Zomboid\Rcon;

use Lowel\LaravelServiceMaker\Services\ServiceFactoryInterface;

class RconServiceFactory implements ServiceFactoryInterface
{
    /**
     * @param array{
     *     name: string,
     *     port: int,
     *     password: string
     * }|array<empty> $params
     */
    public function get(array $params = []): RconServiceInterface
    {
        return new RconService($params['name'], $params['port'], $params['password']);
    }

    public function zomboid(): RconServiceInterface
    {
        return new RconService(config('zomboid.docker.socket'), config('zomboid.rcon.port'), config('zomboid.rcon.password'));
    }
}
