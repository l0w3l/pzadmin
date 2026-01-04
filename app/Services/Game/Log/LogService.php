<?php

declare(strict_types=1);

namespace App\Services\Game\Log;

use App\Data\Game\Log\LogData;
use App\Data\Game\Log\LogItemData;
use App\Data\Game\Log\PlayerLogData;
use App\Data\Game\Log\PlayerOnlineStatusEnum;
use App\Repositories\Game\Log\LogInstanceEnum;
use App\Repositories\Game\Log\LogRepositoryInterface;
use Illuminate\Support\Collection;
use Lowel\LaravelServiceMaker\Services\AbstractService;

class LogService extends AbstractService implements LogServiceInterface
{
    public function __construct(
        public LogRepositoryInterface $logRepository,
    ) {}

    public function readServerConsole(int $limit = 20, int $offset = 0): LogData
    {
        return $this->logRepository->parse(LogInstanceEnum::SERVER_CONSOLE->path(), $limit, $offset);
    }

    public function getPlayersInfo(): array
    {
        $userLogs = $this->logRepository->parseNnAllSubDirectories(base_path('/docker/zomboid/storage/data/Logs/'), '*user.txt');

        /** @var Collection<int, PlayerLogData> $players */
        $players = collect();
        foreach ($userLogs as $userLog) {
            $logsData = $this->logRepository->parse($userLog->getPathname(), PHP_INT_MAX);

            /** @var LogItemData $logDataItem */
            foreach ($logsData->logItems as $logDataItem) {
                $playerLogData = PlayerLogData::fromLogString($logDataItem->message);

                if ($playerLogData && $players->where('steamId', $playerLogData->steamId)->isEmpty()) {
                    $players->push($playerLogData);
                } else {
                    $matches = [];

                    if (preg_match('/\[(.+)] (\d+) ".+" fully connected \(\d+,\d+,\d+\)\./', $logDataItem->message, $matches)) {
                        $player = $players->firstWhere('steamId', $matches[2] ?? null);

                        $player?->setOnline()
                            ->setUpdatedAt($matches[1]);
                    } elseif (preg_match('/\[(.+)] (\d+) ".+" disconnected player \(\d+,\d+,\d+\)\./', $logDataItem->message, $matches)) {
                        $player = $players->firstWhere('steamId', $matches[2] ?? null);

                        $player?->setOffline()
                            ->setUpdatedAt($matches[1]);
                    }
                }
            }
        }

        return $this->sortPlayerLogDataCollection(
            $players->all()
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
                case PlayerOnlineStatusEnum::LOADING:
                    $loading[] = $playerLogData;
                    break;
                case PlayerOnlineStatusEnum::OFFLINE:
                    $offline[] = $playerLogData;
                    break;
            }
        }

        return [...$online, ...$loading, ...$offline];
    }
}
