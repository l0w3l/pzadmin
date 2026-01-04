<?php

namespace App\Data\Game\Log;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;

class LogData extends Data
{
    public function __construct(
        public readonly string $md5,
        #[DataCollectionOf(LogItemData::class)]
        public readonly array $logItems,
    ) {}
}
