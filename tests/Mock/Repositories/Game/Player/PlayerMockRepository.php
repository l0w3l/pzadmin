<?php

declare(strict_types=1);

namespace Tests\Mock\Repositories\Game\Player;

use App\Data\Game\PlayerData;
use App\Models\Game\Player;
use App\Repositories\Game\Player\PlayerRepositoryInterface;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Pagination\Paginator;

class PlayerMockRepository implements PlayerRepositoryInterface
{
    public function allWithPagination(): AbstractPaginator
    {
        $players = [
            ...Player::factory()->count(10)->make(),
        ];

        return new Paginator(PlayerData::collect($players), 10);
    }
}
