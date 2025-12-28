<?php

namespace App\Data\Game;

use phpDocumentor\Reflection\Types\Boolean;
use Spatie\LaravelData\Data;

class PlayerData extends Data
{
    public function __construct(
        readonly public int $id,
        readonly public string $name,
        readonly public string $username,
        readonly public mixed $isDead,
        readonly public ?string $steamid = null,
    ) {}
}
