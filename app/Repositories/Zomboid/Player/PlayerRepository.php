<?php

declare(strict_types=1);

namespace App\Repositories\Zomboid\Player;

use App\Data\Zomboid\PlayerData;
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
