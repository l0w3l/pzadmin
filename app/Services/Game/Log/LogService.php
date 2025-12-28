<?php

declare(strict_types=1);

namespace App\Services\Game\Log;

use App\Repositories\Game\Log\LogRepositoryFactory;
use App\Repositories\Game\LogInstance\LogInstanceEnum;
use App\Repositories\Game\LogInstance\LogInstanceRepositoryInterface;
use App\Services\Abstract\AbstractService;
use Illuminate\Support\Collection;

class LogService extends AbstractService implements LogServiceInterface
{
    public function __construct(
        readonly LogInstanceRepositoryInterface $logInstanceRepository,
        readonly LogRepositoryFactory $logRepositoryFactory
    ) {}

    public function getServerConsoleLogs(): Collection
    {
        $logInstance = $this->logInstanceRepository->findOrCreate(LogInstanceEnum::SERVER_CONSOLE);

        return $this->logRepositoryFactory
            ->getDatabase()
            ->allByInstance($logInstance);
    }

    public function getServerConsoleLogsFromFilesystem(): Collection
    {
        $logInstance = $this->logInstanceRepository->findOrCreate(LogInstanceEnum::SERVER_CONSOLE);

        return $this->logRepositoryFactory
            ->getFilesystem()
            ->allByInstance($logInstance);
    }

    public function saveLogsInDatabase(Collection $logsInstancesData): Collection
    {
        $logInstance = $this->logInstanceRepository->findOrCreate(LogInstanceEnum::SERVER_CONSOLE);

        return $this->logRepositoryFactory->getDatabase()->save($logInstance, $logsInstancesData);
    }

    public function appendLogsInDatabase(Collection $logsInstanceData): Collection
    {
        $logsInstance = $this->logInstanceRepository->findOrCreate(LogInstanceEnum::SERVER_CONSOLE);

        return $this->logRepositoryFactory->getDatabase()->append($logsInstance, $logsInstanceData);
    }

    public function resetLogsInDatabase(): void
    {
        $logInstance = $this->logInstanceRepository->findOrCreate(LogInstanceEnum::SERVER_CONSOLE);

        $this->logRepositoryFactory->getDatabase()->reset($logInstance);
    }
}
