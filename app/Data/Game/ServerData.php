<?php

namespace App\Data\Game;

use App\Enums\Docker\ContainerStatusEnum;
use Spatie\LaravelData\Data;

final class ServerData extends Data
{
    public int $port;

    public string $ip;

    public function __construct(
        public ContainerStatusEnum $status,
    ) {
        $this->port = (int) config('zomboid.port');
        $this->ip = config('zomboid.ip');
    }
}
