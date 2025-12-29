<?php

use App\Enums\Docker\ContainerStatusEnum;
use App\Enums\Models\Game\ServerEnum;
use App\Models\Auth\User;
use App\Services\Game\Zomboid\ZomboidServiceInterface;
use Database\Seeders\DatabaseSeeder;
use Symfony\Component\HttpFoundation\Response;
use Tests\Mock\Services\Zomboid\ZomboidServiceMock;
use Tests\TestCase;

/** @var TestCase $this */
beforeEach(function () {
    $this->seed(DatabaseSeeder::class);
    \Illuminate\Support\Facades\App::bind(ZomboidServiceInterface::class, fn () => new ZomboidServiceMock(ContainerStatusEnum::ACTIVE, ContainerStatusEnum::DOWN));
});

/**
 * @link \App\Http\Controllers\Api\V1\Zomboid\ZomboidController::index()
 */
test('zomboid server status', function () {
    $response = $this->getJson(route('v1.zomboid.index'));

    $response->assertStatus(Response::HTTP_OK)
        ->assertJson([
            'data' => [
                'port' => config('zomboid.port'),
                'ip' => config('zomboid.ip'),
                'name' => ServerEnum::ZOMBOID->value,
                'prefix' => config('app.name'),
                'fullName' => config('app.name').'_'.ServerEnum::ZOMBOID->value,
                'status' => ContainerStatusEnum::ACTIVE->value,
            ],
        ]);
});

/**
 * @link \App\Http\Controllers\Api\V1\Zomboid\ZomboidController::index()
 */
test('zomboid server start', function () {
    $user = User::factory()->create();

    $this->getJson(route('v1.zomboid.start'))
        ->assertStatus(Response::HTTP_UNAUTHORIZED);
    $this->actingAs($user)->getJson(route('v1.zomboid.start'))
        ->assertStatus(Response::HTTP_ACCEPTED);
});

/**
 * @link \App\Http\Controllers\Api\V1\Zomboid\ZomboidController::index()
 */
test('zomboid server down', function () {
    $user = User::factory()->create();

    $this->getJson(route('v1.zomboid.down'))
        ->assertStatus(Response::HTTP_UNAUTHORIZED);
    $this->actingAs($user)->getJson(route('v1.zomboid.down'))
        ->assertStatus(Response::HTTP_ACCEPTED);
});

/**
 * @link \App\Http\Controllers\Api\V1\Zomboid\ZomboidController::index()
 */
test('zomboid server restart', function () {
    $user = User::factory()->create();

    $this->getJson(route('v1.zomboid.restart'))
        ->assertStatus(Response::HTTP_UNAUTHORIZED);
    $this->actingAs($user)->getJson(route('v1.zomboid.restart'))
        ->assertStatus(Response::HTTP_ACCEPTED);
});
