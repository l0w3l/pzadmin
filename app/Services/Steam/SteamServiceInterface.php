<?php

declare(strict_types=1);

namespace App\Services\Steam;

use App\Data\Steam\GetPlayerSummaries\PlayerSummaryData;
use Lowel\LaravelServiceMaker\Services\ServiceInterface;

interface SteamServiceInterface extends ServiceInterface
{
    /**
     * @return PlayerSummaryData[]
     */
    public function getPlayerSummaries(array $playersSteamIds): array;
}
