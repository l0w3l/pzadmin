<?php

declare(strict_types=1);

namespace App\Data\Game\Log;

use Spatie\LaravelData\Data;

class PlayerLogData extends Data
{
    public function __construct(
        public readonly string $steamId,
        public readonly string $guid,
        public readonly PlayerOnlineStatusEnum $online
    ) {}
}
