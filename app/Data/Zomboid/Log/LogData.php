<?php

namespace App\Data\Zomboid\Log;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;

class LogData extends Data
{
    /**
     * @param  LogItemData[]  $logItems
     */
    public function __construct(
        public readonly string $md5,
        #[DataCollectionOf(LogItemData::class)]
        public readonly array $logItems,
    ) {}
}
