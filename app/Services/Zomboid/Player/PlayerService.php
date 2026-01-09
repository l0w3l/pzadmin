<?php

declare(strict_types=1);

namespace App\Services\Zomboid\Player;

use App\Repositories\Zomboid\Player\PlayerRepositoryInterface;
use Illuminate\Pagination\AbstractPaginator;
use Lowel\LaravelServiceMaker\Services\AbstractService;

class PlayerService extends AbstractService implements PlayerServiceInterface
{
    public function __construct(
        protected readonly PlayerRepositoryInterface $playerRepository
    ) {}

    public function getAllPlayersWithPagination(): AbstractPaginator
    {
        return $this->playerRepository->allWithPagination();
    }
}
