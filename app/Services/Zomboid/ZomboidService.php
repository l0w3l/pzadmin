<?php

declare(strict_types=1);

namespace App\Services\Zomboid;

use App\Data\Zomboid\ServerData;
use App\Services\Docker\DockerServiceInterface;
use App\Services\Docker\Enums\ContainerActionEnum;
use Lowel\LaravelServiceMaker\Services\AbstractService;

class ZomboidService extends AbstractService implements ZomboidServiceInterface
{
    public function __construct(
        public DockerServiceInterface $zomboidDockerContainer,
    ) {}

    public function getServer(): ServerData
    {
        $serverInspection = $this->zomboidDockerContainer->status();

        return ServerData::fromInspectionResult($serverInspection);
    }

    public function doStart(): bool
    {
        return $this->zomboidDockerContainer->operate(ContainerActionEnum::UP);
    }

    public function doDown(): bool
    {
        return $this->zomboidDockerContainer->operate(ContainerActionEnum::DOWN);
    }

    public function doRestart(): bool
    {
        return $this->zomboidDockerContainer->operate(ContainerActionEnum::RESTART);
    }
}
