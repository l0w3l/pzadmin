<?php

declare(strict_types=1);

namespace App\Data\Steam\GetPlayerSummaries;

use Spatie\LaravelData\Data;

class PlayerSummaryData extends Data
{
    public function __construct(
        public readonly string $steamid,
        public readonly string $personaname,
        public readonly ?string $profileurl = null,
    )
    {
    }
}
