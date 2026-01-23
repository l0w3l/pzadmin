<?php

declare(strict_types=1);

namespace App\Services\Zomboid\Log;

use App\Data\Zomboid\Log\LogData;
use App\Data\Zomboid\Log\LogItemData;
use App\Data\Zomboid\Log\PlayerLogData;
use App\Data\Zomboid\Log\PlayerOnlineStatusEnum;
use App\Repositories\Zomboid\Log\Enum\LogInstanceEnum;
use App\Repositories\Zomboid\Log\LogRepositoryInterface;
use Illuminate\Support\Collection;
use Lowel\LaravelServiceMaker\Services\AbstractService;

class LogService extends AbstractService implements LogServiceInterface
{
    public function __construct(
        public LogRepositoryInterface $logRepository,
    ) {}

    public function readServerConsole(int $limit = PHP_INT_MAX, int $offset = 0): LogData
    {
        return $this->logRepository->parseReverse(LogInstanceEnum::SERVER_CONSOLE->path(), $limit, $offset);
    }

    public function readServerConsoleCursor(int $leftRange, int $rightRange): LogData
    {
        return $this->logRepository->parseCursor(LogInstanceEnum::SERVER_CONSOLE->path(), $leftRange, $rightRange);
    }

    public function getServerConsoleMD5(): string
    {
        return $this->logRepository->getMD5Of(LogInstanceEnum::SERVER_CONSOLE->path());
    }

    public function getPlayersInfo(): array
    {
        $userLogs = $this->logRepository->parseAllSubDirectories(base_path('/docker/zomboid/storage/data/Logs/'), '*user.txt');

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
                    if (preg_match('/\[(.+)] (\d+) "(.+)" fully connected \(\d+,\d+,\d+\)\./', $logDataItem->message, $matches)) {
                        $player = $players->firstWhere('steamId', $matches[2]);

                        $player?->setOnline()
                            ->setName($matches[3])
                            ->setUpdatedAt($matches[1]);
                    } elseif (preg_match('/\[(.+)] (\d+) ".+" disconnected player \(\d+,\d+,\d+\)\./', $logDataItem->message, $matches)
                        || preg_match('/\[(.+)] Connection disconnect index=\d+ guid=\d+ id=(\d+)\./', $logDataItem->message, $matches)) {
                        $player = $players->firstWhere('steamId', $matches[2]);

                        $player?->setOffline()
                            ->setUpdatedAt($matches[1]);
                    }
                }
            }
        }

        return $this->sortPlayerLogDataCollection(
            $this->sortByUpdateAt(
                $players->all()
            )
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

    /**
     * @param  array<PlayerLogData>  $playerLogDataCollection
     * @return array<PlayerLogData>
     */
    private function sortByUpdateAt(array $playerLogDataCollection): array
    {
        usort($playerLogDataCollection, function ($a, $b) {
            return (int) ($b->updatedAt > $a->updatedAt);
        });

        return $playerLogDataCollection;
    }
}
