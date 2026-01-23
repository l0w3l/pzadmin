<?php

declare(strict_types=1);

namespace App\Services\Docker\Enums;

enum ContainerActionEnum
{
    case UP;
    case DOWN;
    case RESTART;
}
