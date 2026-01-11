<?php

declare(strict_types=1);

namespace App\Services\Zomboid\Rcon;

use App\Enums\Models\Game\ServerEnum;
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
        return new RconService(ServerEnum::ZOMBOID->value, config('zomboid.rcon.port'), config('zomboid.rcon.password'));
    }
}
