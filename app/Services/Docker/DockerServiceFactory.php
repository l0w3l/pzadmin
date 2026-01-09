<?php

declare(strict_types=1);

namespace App\Services\Docker;

use App\Enums\Models\Game\ServerEnum;
use Lowel\LaravelServiceMaker\Services\ServiceFactoryInterface;

class DockerServiceFactory implements ServiceFactoryInterface
{
    /**
     * @param array{
     *     containerId: string
     * }|array<empty> $params
     */
    public function get(array $params = []): DockerServiceInterface
    {
        return new DockerService(
            $params['containerId'] ?? throw new \RuntimeException("Parameter 'containerId' was not provided."),
        );
    }

    public function zomboid(): DockerServiceInterface
    {
        return new DockerService(ServerEnum::ZOMBOID->name());
    }
}
