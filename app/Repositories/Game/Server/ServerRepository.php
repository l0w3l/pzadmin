<?php

declare(strict_types=1);

namespace App\Repositories\Game\Server;

use App\Data\Game\ServerData;
use App\Enums\Docker\ContainerStatusEnum;
use App\Enums\Models\Game\ServerEnum;
use App\Models\Game\Server;
use Lowel\LaravelServiceMaker\Repositories\AbstractRepository;

class ServerRepository extends AbstractRepository implements ServerRepositoryInterface
{
    public function findServer(ServerEnum $serverEnum): ServerData
    {
        $server = Server::server($serverEnum)->first();

        return ServerData::from(
            $server
        );
    }

    public function updateStatus(ServerEnum $serverEnum, ContainerStatusEnum $statusEnum): ServerData
    {
        $server = Server::server($serverEnum)->first();

        $server->fill(['status' => $statusEnum])->save();

        return ServerData::from($server);
    }
}
