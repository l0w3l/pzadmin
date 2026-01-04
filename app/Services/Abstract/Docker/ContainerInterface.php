<?php

declare(strict_types=1);

namespace App\Services\Abstract\Docker;

use App\Enums\Docker\ContainerActionEnum;
use App\Throwable\Exceptions\ContainerOperationException;
use Lowel\Docker\Response\DTO\Container;

/**
 * Describe all main operations with a docker container
 */
interface ContainerInterface
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
