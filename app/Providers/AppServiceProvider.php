<?php

namespace App\Providers;

use App\Models\Auth\PersonalAccessToken;
use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /** @phpstan-ignore-next-line  */
        Relation::enforceMorphMap([
            PersonalAccessToken::TOKENABLE_USER => User::class,
        ]);

        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
    }
}
