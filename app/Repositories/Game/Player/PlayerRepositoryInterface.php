<?php

declare(strict_types=1);

namespace App\Repositories\Game\Player;

use App\Data\Game\PlayerData;
use Illuminate\Pagination\AbstractPaginator;
use Lowel\LaravelServiceMaker\Repositories\RepositoryInterface;

interface PlayerRepositoryInterface extends RepositoryInterface
{
    /**
     * @return AbstractPaginator<PlayerData>
     */
    public function allWithPagination(): AbstractPaginator;
}
