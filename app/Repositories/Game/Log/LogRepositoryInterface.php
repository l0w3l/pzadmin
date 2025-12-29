<?php

declare(strict_types=1);

namespace App\Repositories\Game\Log;

use App\Data\Game\Log\LogData;
use Lowel\LaravelServiceMaker\Repositories\RepositoryInterface;

interface LogRepositoryInterface extends RepositoryInterface
{
    public function parse(LogInstanceEnum $logInstanceEnum, int $limit = 20, int $offset = 0): LogData;
}
