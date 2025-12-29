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
        $serverStatus = $this->zomboidDockerContainer->status();

        return new ServerData($serverStatus);
    }

    public function updateStatus(?callable $onChange = null): void
    {
        $serverData = $this->getServer();
        $newStatusEnum = $this->zomboidDockerContainer->status();

        if ($serverData->status !== $newStatusEnum) {
            $onChange(
                $this->serverRepository->updateStatus($serverData->name, $newStatusEnum)
            );
        }
    }

    public function doStart(): bool
    {
        $result = $this->zomboidDockerContainer->operate(ContainerActionEnum::UP);

        return $result->isOk();
    }

    public function doDown(): bool
    {
        $result = $this->zomboidDockerContainer->operate(ContainerActionEnum::DOWN);

        return $result->isOk();
    }

    public function doRestart(): bool
    {
        $result = $this->zomboidDockerContainer->operate(ContainerActionEnum::RESTART);

        return $result->isOk();
    }
}
