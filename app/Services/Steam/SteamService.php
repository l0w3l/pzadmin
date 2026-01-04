<?php

declare(strict_types=1);

namespace App\Services\Steam;

use App\Data\Game\Log\PlayerLogData;
use App\Data\Steam\GetPlayerSummaries\PlayerSummaryData;
use GuzzleHttp\Client;
use Lowel\LaravelServiceMaker\Services\AbstractService;

class SteamService extends AbstractService implements SteamServiceInterface
{
    protected Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://api.steampowered.com/',
            'timeout' => 5,
        ]);
    }

    public function getPlayerSummaries(array $playersSteamIds): array
    {
        $response = $this->client->get('ISteamUser/GetPlayerSummaries/v2/', [
            'query' => [
                'key' => config('zomboid.steam_key'),
                'steamids' => implode(',', $playersSteamIds),
            ],
        ]);

        $response = json_decode($response->getBody()->getContents(), true);

        return PlayerSummaryData::collect($response['response']['players']);
    }

    public function getPlayerSummariesForPlayerLogData(PlayerLogData ...$playerDataCollection): array
    {
        $playerSteamIds = [];

        foreach ($playerDataCollection as $playerData) {
            $playerSteamIds[] = $playerData->steamId;
        }

        $playerSummary = $this->getPlayerSummaries($playerSteamIds);

        $syncOrder = [];
        foreach ($playerDataCollection as $playerData) {
            foreach ($playerSummary as $playerSummaries) {
                if ($playerSummaries->steamid === $playerData->steamId) {
                    $syncOrder[] = $playerSummaries;
                }
            }
        }

        return $syncOrder;
    }
}
