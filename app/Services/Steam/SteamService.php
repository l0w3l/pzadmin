<?php

declare(strict_types=1);

namespace App\Services\Steam;

use App\Data\Steam\GetPlayerSummaries\PlayerSummaryData;
use GuzzleHttp\Client;
use Illuminate\Support\Collection;
use Lowel\LaravelServiceMaker\Services\AbstractService;
use App\Services\Steam\SteamServiceInterface;

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
            ]
        ]);

        $response = json_decode($response->getBody()->getContents(), true);

        return PlayerSummaryData::collect($response['response']['players']);
    }

}
