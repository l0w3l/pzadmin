<?php

namespace App\Data\Zomboid\Log;

use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;

class LogData extends Data
{
    #[Computed]
    public readonly int $lastId;

    /**
     * @param  LogItemData[]  $logItems
     */
    public function __construct(
        public readonly string $md5,
        #[DataCollectionOf(LogItemData::class)]
        public readonly array $logItems,
    ) {
        $this->lastId = $logItems[count($logItems) - 1]->id ?? -1;
    }
}
