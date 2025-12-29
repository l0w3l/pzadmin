<?php

declare(strict_types=1);

namespace App\Repositories\Game\Server;

use App;
use Lowel\LaravelServiceMaker\Repositories\RepositoryFactoryInterface;

final class ServerRepositoryFactory implements RepositoryFactoryInterface
{
    public function get(): ServerRepositoryInterface
    {
        return App::make(ServerRepository::class);
    }
}
