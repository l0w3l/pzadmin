<?php

declare(strict_types=1);

namespace App\Services\Abstract\Docker;

use App\Enums\Docker\ContainerActionEnum;
use Lowel\Docker\ClientResponseHandlerInterface as DockerClientResponseHandlerInterface;
use Lowel\Docker\Response\DTO\Container;

abstract readonly class AbstractContainer implements ContainerInterface
{
    public function __construct(
        protected DockerClientResponseHandlerInterface $dockerClientResponseHandler,
        protected string $containerId,
    ) {}

    public function status(): Container
    {
        return $this->dockerClientResponseHandler->containerInspect($this->containerId);
    }

    public function operate(ContainerActionEnum $action): bool
    {
        return match ($action) {
            ContainerActionEnum::UP => $this->dockerClientResponseHandler->containerStart($this->containerId),
            ContainerActionEnum::DOWN => $this->dockerClientResponseHandler->containerStop($this->containerId),
            ContainerActionEnum::RESTART => $this->dockerClientResponseHandler->containerRestart($this->containerId)
        };
    }
}
