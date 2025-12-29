<?php

namespace App\Data\Game;

use Spatie\LaravelData\Data;

class PlayerData extends Data
{
    public readonly string $username;

    public function __construct(
        readonly public int $id,
        readonly public string $name,
        string $username,
        readonly public mixed $isDead,
        readonly public ?string $steamid = null,
    ) {
        $this->username = $username[0].'**'.$username[-1];
    }
}
