<?php

declare(strict_types=1);

namespace App\Repositories\Game\Player;

use Illuminate\Support\Facades\App;
use Lowel\LaravelServiceMaker\Repositories\RepositoryFactoryInterface;

class PlayerRepositoryFactory implements RepositoryFactoryInterface
{
    public function get(): PlayerRepositoryInterface
    {
        return App::make(PlayerRepository::class);
    }
}
