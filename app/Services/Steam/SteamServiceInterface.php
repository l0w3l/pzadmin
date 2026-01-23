<?php

declare(strict_types=1);

namespace App\Services\Steam;

use App\Data\Steam\GetPlayerSummaries\PlayerSummaryData;
use App\Data\Zomboid\Log\PlayerLogData;
use Lowel\LaravelServiceMaker\Services\ServiceInterface;

interface SteamServiceInterface extends ServiceInterface
{
    /**
     * @param  string[]|int[]  $playersSteamIds
     * @return PlayerSummaryData[]
     */
    public function getPlayerSummaries(array $playersSteamIds): array;

    /**
     * @return PlayerSummaryData[]
     */
    public function getPlayerSummariesForPlayerLogData(PlayerLogData ...$playerDataCollection): array;
}
