<?php

declare(strict_types=1);

namespace App\Services\Game\Zomboid;

use App\Data\Game\ServerData;
use App\Enums\Docker\ContainerActionEnum;
use App\Repositories\Game\Server\ServerRepositoryInterface;
use App\Services\Abstract\AbstractService;
use App\Services\Game\Zomboid\Docker\ZomboidDockerContainer;

class ZomboidService extends AbstractService implements ZomboidServiceInterface
{
    public function __construct(
        public ZomboidDockerContainer $zomboidDockerContainer,
        protected ServerRepositoryInterface $serverRepository,
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
