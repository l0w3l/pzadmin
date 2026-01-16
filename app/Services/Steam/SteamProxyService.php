<?php

declare(strict_types=1);

namespace App\Services\Steam;

use App\Data\Steam\GetPlayerSummaries\PlayerSummaryData;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class SteamProxyService extends SteamService implements SteamServiceInterface
{
    public function getPlayerSummaries(array $playersSteamIds): array
    {
        /** @var Collection<int, PlayerSummaryData> $cached */
        $cached = new Collection(Cache::get('steam.player_summaries', []));

        if ($cached->isNotEmpty()) {
            $result = [];
            $newSteamIds = [];

            foreach ($playersSteamIds as $steamId) {
                $steamPlayerFromCache = $cached->where('steamid', $steamId)->first();

                if ($steamPlayerFromCache) {
                    $result[] = $steamPlayerFromCache;
                } else {
                    $newSteamIds[] = $steamId;
                }

            }
            if (! empty($newSteamIds)) {
                $result = [...$result, ...parent::getPlayerSummaries($newSteamIds)];

                Cache::set('steam.player_summaries', $result, now()->addHour());
            }

            return $result;
        } else {
            $result = parent::getPlayerSummaries($playersSteamIds);

            Cache::set('steam.player_summaries', $result, now()->addHour());

            return $result;
        }
    }
}
