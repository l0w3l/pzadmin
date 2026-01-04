<?php

namespace App\Data\Auth;

use Spatie\LaravelData\Data;

class InviteData extends Data
{
    public function __construct(
        public readonly string $id,
        public readonly string $hash,
        public readonly int $limit,
    ) {}
}
