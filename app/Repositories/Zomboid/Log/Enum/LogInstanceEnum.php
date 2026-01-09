<?php

declare(strict_types=1);

namespace App\Repositories\Zomboid\Log\Enum;

enum LogInstanceEnum: string
{
    case SERVER_CONSOLE = 'docker/zomboid/storage/data/server-console.txt';

    public function path(): string
    {
        return base_path($this->value);
    }
}
