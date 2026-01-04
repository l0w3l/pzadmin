<?php

declare(strict_types=1);

namespace App\Data\Game\Log;

use Spatie\LaravelData\Data;

class LogItemData extends Data
{
    public function __construct(
        public readonly int $id,
        public readonly string $message,
    ) {}
}
