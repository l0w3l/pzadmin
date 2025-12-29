<?php

declare(strict_types=1);

namespace App\Services\Game\Player;

use App\Repositories\Game\Player\PlayerRepositoryInterface;
use App\Services\Abstract\AbstractService;
use Illuminate\Pagination\AbstractPaginator;

class PlayerService extends AbstractService implements PlayerServiceInterface
{
    public function __construct(
        readonly protected PlayerRepositoryInterface $playerRepository
    ) {}

    public function getAllPlayersWithPagination(): AbstractPaginator
    {
        return $this->playerRepository->allWithPagination();
    }
}
