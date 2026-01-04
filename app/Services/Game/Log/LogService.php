<?php

declare(strict_types=1);

namespace App\Services\Game\Log;

use App\Data\Game\Log\LogData;
use App\Data\Game\Log\LogItemData;
use App\Data\Game\Log\PlayerLogData;
use App\Data\Game\Log\PlayerOnlineStatusEnum;
use App\Repositories\Game\Log\LogInstanceEnum;
use App\Repositories\Game\Log\LogRepositoryInterface;
use Illuminate\Support\Str;
use Lowel\LaravelServiceMaker\Services\AbstractService;

class LogService extends AbstractService implements LogServiceInterface
{
    public function __construct(
        public LogRepositoryInterface $logRepository,
    ) {}

    public function readServerConsole(int $limit = 20, int $offset = 0): LogData
    {
        return $this->logRepository->parse(LogInstanceEnum::SERVER_CONSOLE, $limit, $offset);
    }

    public function getPlayersInfo(): array
    {
        $limit = PHP_INT_MAX;
        $offset = 0;

        $logsData = $this->readServerConsole($limit, $offset);

        $players = [];
        $lastSteamId = null;

        /** @var LogItemData $logItem */
        foreach ($logsData->logItems as $logItem) {
            if (strlen($steamId = Str::match('/Steam client ([0-9]+) is initiating a connection/', $logItem->message)) > 0) {
                $lastSteamId = (int) $steamId;

                if ($players[$steamId] ?? false) {
                    continue;
                }

                $players[$steamId] = [
                    'steamId' => $lastSteamId,
                    'guid' => null,
                    'online' => PlayerOnlineStatusEnum::LOADING,
                ];
            } elseif (strlen($guid = Str::match('/Connected new client ([0-9]+) ID/', $logItem->message)) > 0) {
                $players[$lastSteamId]['guid'] = $guid;
            } elseif (strlen(Str::match('/connection: guid=([0-9]+) \[RakNet] "connection-lost"/', $logItem->message)) > 0) {
                $players[$lastSteamId]['online'] = PlayerOnlineStatusEnum::OFFLINE;
            } elseif (strlen(Str::match('/connection: guid=([0-9]+) \[disconnect] "receive-disconnect"/', $logItem->message)) > 0) {
                $players[$lastSteamId]['online'] = PlayerOnlineStatusEnum::OFFLINE;
            } elseif (strlen(Str::match('/connection: guid=([0-9]+) \[fully-connected] ""/', $logItem->message)) > 0) {
                $players[$lastSteamId]['online'] = PlayerOnlineStatusEnum::ONLINE;
            }
        }

        $players = array_filter($players, fn (array $player) => $player['guid'] ?? false);

        return $this->sortPlayerLogDataCollection(
            PlayerLogData::collect(array_values($players))
        );
    }

    /**
     * @param  PlayerLogData[]  $playerLogDataCollection
     * @return PlayerLogData[]
     */
    private function sortPlayerLogDataCollection(array $playerLogDataCollection): array
    {
        $online = [];
        $loading = [];
        $offline = [];

        foreach ($playerLogDataCollection as $playerLogData) {
            switch ($playerLogData->online) {
                case PlayerOnlineStatusEnum::ONLINE:
                    $online[] = $playerLogData;
                    break;
                case PlayerOnlineStatusEnum::OFFLINE:
                    $loading[] = $playerLogData;
                    break;
                case PlayerOnlineStatusEnum::LOADING:
                    $offline[] = $playerLogData;
                    break;
            }
        }

        return [...$online, ...$loading, ...$offline];
    }
}
