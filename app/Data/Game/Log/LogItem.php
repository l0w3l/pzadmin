<?php

declare(strict_types=1);

namespace App\Data\Game\Log;

use Spatie\LaravelData\Data;

class LogItem extends Data
{
    public function __construct(
        readonly public int $id,
        readonly public string $message,
    ) {}
}
