<?php

declare(strict_types=1);

namespace App\Services\Game\Log;

use App\Data\Game\Log\LogData;
use App\Data\Game\Log\PlayerLogData;
use Lowel\LaravelServiceMaker\Services\ServiceInterface;

interface LogServiceInterface extends ServiceInterface
{
    public function readServerConsole(int $limit = PHP_INT_MAX, int $offset = 0): LogData;
    public function getServerConsoleMD5(): string;

    /**
     * @return PlayerLogData[]
     */
    public function getPlayersInfo(): array;
}
