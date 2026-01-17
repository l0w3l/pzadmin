<?php

declare(strict_types=1);

namespace App\Services\Steam;

use App\Data\Steam\GetPlayerSummaries\PlayerSummaryData;
use App\Data\Zomboid\Log\PlayerLogData;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Lowel\LaravelServiceMaker\Services\AbstractService;
use Psr\Http\Message\RequestInterface;

class SteamService extends AbstractService implements SteamServiceInterface
{
    private Client $client;

    public function __construct(string $steamApiKey)
    {
        $stack = HandlerStack::create();
        $stack->push($this->steamKeyMiddleware($steamApiKey));

        $this->client = new Client([
            'base_uri' => 'https://api.steampowered.com/',
            'timeout' => 5,
            'handler' => $stack,
        ]);
    }

    public function getPlayerSummaries(array $playersSteamIds): array
    {
        if (empty($playersSteamIds)) {
            return [];
        }

        $response = $this->client->get('ISteamUser/GetPlayerSummaries/v2/', [
            'query' => [
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

    private function steamKeyMiddleware(string $apiKey): callable
    {
        return function (callable $handler) use ($apiKey) {
            return function (RequestInterface $request, array $options) use ($handler, $apiKey) {

                $uri = $request->getUri();
                parse_str($uri->getQuery(), $query);

                $query['key'] ??= $apiKey;

                return $handler(
                    $request->withUri(
                        $uri->withQuery(http_build_query($query))
                    ),
                    $options
                );
            };
        };
    }
}
