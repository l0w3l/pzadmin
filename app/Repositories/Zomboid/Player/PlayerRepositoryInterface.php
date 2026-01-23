<?php

declare(strict_types=1);

namespace App\Repositories\Zomboid\Player;

use App\Data\Zomboid\PlayerData;
use Illuminate\Pagination\AbstractPaginator;
use Lowel\LaravelServiceMaker\Repositories\RepositoryInterface;

interface PlayerRepositoryInterface extends RepositoryInterface
{
    /**
     * @return AbstractPaginator<int, PlayerData>
     */
    public function allWithPagination(): AbstractPaginator;
}
