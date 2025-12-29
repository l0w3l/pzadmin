<?php

declare(strict_types=1);

namespace App\Repositories\Game\Player;

use App\Data\Game\PlayerData;
use App\Models\Game\Player;
use Illuminate\Pagination\AbstractPaginator;
use Lowel\LaravelServiceMaker\Repositories\AbstractRepository;

class PlayerRepository extends AbstractRepository implements PlayerRepositoryInterface
{
    public function allWithPagination(): AbstractPaginator
    {
        $players = Player::paginate();

        return PlayerData::collect($players);
    }
}
