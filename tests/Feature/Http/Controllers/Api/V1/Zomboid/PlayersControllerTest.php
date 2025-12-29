<?php

use App\Repositories\Game\Player\PlayerRepositoryInterface;
use Database\Seeders\DatabaseSeeder;
use Symfony\Component\HttpFoundation\Response;
use Tests\Mock\Repositories\Game\Player\PlayerMockRepository;
use Tests\TestCase;

/** @var TestCase $this */
beforeEach(function () {
    $this->seed(DatabaseSeeder::class);

    $this->app->bind(PlayerRepositoryInterface::class, fn () => new PlayerMockRepository);
});

/**
 * @link \App\Http\Controllers\Api\V1\Zomboid\PlayersController::index()
 */
test('players with pagination test', function () {
    $response = $this->getJson(route('v1.zomboid.players.index'));

    $response->assertStatus(Response::HTTP_OK)
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'name',
                    'username',
                    'isDead',
                    'steamid',
                ],
            ],
        ])->assertJsonCount(10, 'data');
});
