<?php

namespace App\Http\Resources\V1\Player;

use App\Models\Game\Player;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Player
 */
class PlayerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'username' => $this->username,
            'is_dead' => $this->isDead,
            'steam_id' => $this->steamid,
        ];
    }
}
