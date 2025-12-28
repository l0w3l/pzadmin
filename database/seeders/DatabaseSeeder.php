<?php

namespace Database\Seeders;

use App\Models\Auth\User;
use Database\Seeders\Game\LogInstanceSeeder;
use Database\Seeders\Game\LogSeeder;
use Database\Seeders\Game\ServerSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([ServerSeeder::class, LogInstanceSeeder::class]);

        if (config('app.debug')) {
            User::factory()->create([
                'username' => 'test',
                'password' => config('debug.user.password'),
            ]);

            $this->call([
                LogSeeder::class,
            ]);
        }
    }
}
