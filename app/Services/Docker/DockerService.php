<?php

declare(strict_types=1);

namespace App\Services\Docker;

use App\Services\Docker\Enums\ContainerActionEnum;
use Illuminate\Support\Facades\App;
use Lowel\Docker\ClientFactory as DockerClientFactory;
use Lowel\Docker\ClientResponseHandlerInterface as DockerClientResponseHandlerInterface;
use Lowel\Docker\Response\DTO\Inspect\Container;
use Lowel\LaravelServiceMaker\Services\AbstractService;

class DockerService extends AbstractService implements DockerServiceInterface
{
    protected DockerClientResponseHandlerInterface $dockerClientResponseHandler;

    public function __construct(
        protected string $containerId,
    ) {
        $this->dockerClientResponseHandler = App::make(DockerClientFactory::class)
            ->getClientWithHandler();
    }

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
