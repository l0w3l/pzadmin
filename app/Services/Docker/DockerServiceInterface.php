<?php

declare(strict_types=1);

namespace App\Services\Docker;

use App\Services\Docker\Enums\ContainerActionEnum;
use App\Throwable\Exceptions\ContainerOperationException;
use Lowel\Docker\Response\DTO\Inspect\Container;
use Lowel\LaravelServiceMaker\Services\ServiceInterface;

interface DockerServiceInterface extends ServiceInterface
{
    /**
     * Get status
     */
    public function status(): Container;

    /**
     * Do some actions like up, down or restart container
     *
     * @throws ContainerOperationException
     */
    public function operate(ContainerActionEnum $action): bool;
}
