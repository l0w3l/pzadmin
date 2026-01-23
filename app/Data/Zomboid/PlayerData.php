<?php

namespace App\Data\Zomboid;

use Spatie\LaravelData\Data;

class PlayerData extends Data
{
    public readonly string $username;

    public function __construct(
        public readonly int $id,
        public readonly string $name,
        string $username,
        public readonly mixed $isDead,
        public readonly ?string $steamid = null,
    ) {
        $this->username = $username;
    }
}
