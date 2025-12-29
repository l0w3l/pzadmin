<?php

namespace App\Data\Game\Log;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;

class LogData extends Data
{
    public function __construct(
        readonly public string $md5,
        #[DataCollectionOf(LogItem::class)]
        readonly public array $logItems,
    ) {}
}
