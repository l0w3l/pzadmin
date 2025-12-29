<?php

declare(strict_types=1);

namespace App\Services\Game\Player;

use App\Services\Abstract\ServiceFactoryInterface;
use Illuminate\Support\Facades\App;

class PlayerServiceFactory implements ServiceFactoryInterface
{
    public function get(): PlayerServiceInterface
    {
        return App::make(PlayerService::class);
    }
}
