<?php

declare(strict_types=1);

namespace App\Services\Game\Log;

use App\Data\Game\Log\LogData;
use App\Data\Game\PlayerData;
use Illuminate\Support\Collection;
use Lowel\LaravelServiceMaker\Services\ServiceInterface;

interface LogServiceInterface extends ServiceInterface
{
    public function readServerConsole(int $limit = 20, int $offset = 0): LogData;

    /**
     * @return PlayerData[]
     */
    public function getPlayersInfo(): array;
}
