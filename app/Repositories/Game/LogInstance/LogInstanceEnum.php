<?php

declare(strict_types=1);

namespace App\Repositories\Game\LogInstance;

enum LogInstanceEnum: string
{
    case SERVER_CONSOLE = 'SERVER_CONSOLE';

    public function path(): string
    {
        return match ($this) {
            self::SERVER_CONSOLE => config('zomboid.logs.server_console')
        };
    }

    public function checksum(): string
    {
        return md5_file($this->path());
    }
}

