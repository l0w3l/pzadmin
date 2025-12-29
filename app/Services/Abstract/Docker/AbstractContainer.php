<?php

declare(strict_types=1);

namespace App\Services\Abstract\Docker;

use App\Enums\Docker\ContainerActionEnum;
use App\Enums\Docker\ContainerStatusEnum;
use Lowel\Docker\ClientResponseHandlerInterface as DockerClientResponseHandlerInterface;
use Lowel\Docker\Exceptions\ContainerNotFoundException;

abstract readonly class AbstractContainer implements ContainerInterface
{
    public function __construct(
        protected DockerClientResponseHandlerInterface $dockerClientResponseHandler,
        protected string $containerId,
    ) {}

    public function status(): ContainerStatusEnum
    {
        try {
            $containerData = $this->dockerClientResponseHandler->containerInspect($this->containerId);
        } catch (ContainerNotFoundException) {
            return ContainerStatusEnum::DOWN;
        }

        if ($containerData->isRestarting()) {
            return ContainerStatusEnum::RESTARTING;
        } elseif ($containerData->isPaused()) {
            return ContainerStatusEnum::PAUSED;
        } elseif ($containerData->isDead() || $containerData->isStopped()) {
            return ContainerStatusEnum::DOWN;
        } elseif ($containerData->isRunning()) {
            return ContainerStatusEnum::ACTIVE;
        } else {
            return ContainerStatusEnum::ERROR;
        }
    }

    public function operate(ContainerActionEnum $action): ContainerResponseInterface
    {
        $result = match ($action) {
            ContainerActionEnum::UP => $this->dockerClientResponseHandler->containerStart($this->containerId),
            ContainerActionEnum::DOWN => $this->dockerClientResponseHandler->containerStop($this->containerId),
            ContainerActionEnum::RESTART => $this->dockerClientResponseHandler->containerRestart($this->containerId)
        };

        return new ContainerResponse($result);
    }
}
