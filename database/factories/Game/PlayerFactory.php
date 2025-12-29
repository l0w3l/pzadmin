<?php

declare(strict_types=1);

namespace Database\Factories\Game;

use Illuminate\Database\Eloquent\Factories\Factory;

class PlayerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'id' => fake()->randomNumber(),
            'name' => fake()->userName(),
            'username' => fake()->userName(),
            'isDead' => fake()->boolean(),
            'steamid' => fake()->uuid(),
        ];
    }
}
