<?php

declare(strict_types=1);

namespace App\Services\Game\Log;

use App\Data\Game\Log\LogData;
use App\Repositories\Game\Log\LogInstanceEnum;
use App\Repositories\Game\Log\LogRepositoryInterface;
use Lowel\LaravelServiceMaker\Services\AbstractService;

class LogService extends AbstractService implements LogServiceInterface
{
    public function __construct(
        public LogRepositoryInterface $logRepository,
    ) {}

    public function readServerConsole(int $limit = 20, int $offset = 0): LogData
    {
        return $this->logRepository->parse(LogInstanceEnum::SERVER_CONSOLE, $limit, $offset);
    }
}
