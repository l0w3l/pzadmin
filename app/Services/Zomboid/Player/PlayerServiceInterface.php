<?php

declare(strict_types=1);

namespace App\Services\Zomboid\Player;

use App\Data\Zomboid\PlayerData;
use Illuminate\Pagination\AbstractPaginator;
use Lowel\LaravelServiceMaker\Services\ServiceInterface;

interface PlayerServiceInterface extends ServiceInterface
{
    /**
     * @return AbstractPaginator<int, PlayerData>
     */
    public function getAllPlayersWithPagination(): AbstractPaginator;
}
