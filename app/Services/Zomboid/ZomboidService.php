<?php

declare(strict_types=1);

namespace App\Services\Zomboid;

use App\Data\Zomboid\ServerData;
use App\Models\ZomboidBackup;
use App\Services\Docker\DockerServiceInterface;
use App\Services\Docker\Enums\ContainerActionEnum;
use App\Services\Zomboid\Backup\BackupServiceInterface;
use App\Services\Zomboid\Rcon\RconServiceFactory;
use Lowel\LaravelServiceMaker\Services\AbstractService;

class ZomboidService extends AbstractService implements ZomboidServiceInterface
{
    public function __construct(
        public BackupServiceInterface $backupService,
        public DockerServiceInterface $zomboidDockerContainer,
        public RconServiceFactory $rconServiceFactory,
    ) {}

    public function getServer(): ServerData
    {
        $serverInspection = $this->zomboidDockerContainer->status();

        return ServerData::fromInspectionResult($serverInspection);
    }

    public function doStart(): bool
    {
        $lastBackup = ZomboidBackup::latest()->first();

        if ($lastBackup === null || $lastBackup->hash !== $this->backupService->configHash()) {
            $this->backupService->backup();
        }

        return $this->zomboidDockerContainer->operate(ContainerActionEnum::UP);
    }

    public function doStop(): bool
    {
        $this->rconServiceFactory->zomboid()->save();

        return $this->zomboidDockerContainer->operate(ContainerActionEnum::DOWN);
    }

    public function doRestart(): bool
    {
        return $this->doStop() && $this->doStart();
    }
}
