<?php

declare(strict_types=1);

namespace App\Services\Docker\Enums;

enum ContainerStatusEnum: string
{
    case ACTIVE = 'active';
    case DOWN = 'down';
    case PENDING = 'pending';
    case RESTARTING = 'restarting';
    case PAUSED = 'paused';

    case BACKUP = 'backup';
    case ERROR = 'error';

    public function isActive(): bool
    {
        return $this === self::ACTIVE;
    }

    public function isDown(): bool
    {
        return $this === self::DOWN;
    }

    public function isPending(): bool
    {
        return $this === self::PENDING;
    }

    public function isRestart(): bool
    {
        return $this === self::RESTARTING;
    }

    public function isPaused(): bool
    {
        return $this === self::PAUSED;
    }

    public function isError(): bool
    {
        return $this === self::ERROR;
    }
}
