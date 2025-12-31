<?php

declare(strict_types=1);

namespace App\Services\Steam;

use App\Data\Steam\GetPlayerSummaries\PlayerSummaryData;
use Illuminate\Support\Collection;
use Lowel\LaravelServiceMaker\Services\ServiceInterface;

interface SteamServiceInterface extends ServiceInterface
{
    /**
     * @param array $playersSteamIds
     * @return PlayerSummaryData[]
     */
    public function getPlayerSummaries(array $playersSteamIds): array;
}
