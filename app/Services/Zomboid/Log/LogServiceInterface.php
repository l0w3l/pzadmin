<?php

declare(strict_types=1);

namespace App\Services\Zomboid\Log;

use App\Data\Zomboid\Log\LogData;
use App\Data\Zomboid\Log\PlayerLogData;
use Lowel\LaravelServiceMaker\Services\ServiceInterface;

interface LogServiceInterface extends ServiceInterface
{
    public function readServerConsole(int $limit = PHP_INT_MAX, int $offset = 0): LogData;

    public function readServerConsoleCursor(int $leftRange, int $rightRange): LogData;

    public function getServerConsoleMD5(): string;

    /**
     * @return PlayerLogData[]
     */
    public function getPlayersInfo(): array;
}
