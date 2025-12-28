<?php

namespace App\Http\Resources\V1\Servers\Zomboid;

use App\Http\Resources\Abstract\AbstractResource;
use App\Http\Resources\V1\Player\PlayerResource;
use App\Models\Game\Server;
use Illuminate\Http\Request;

/**
 * @mixin Server
 */
class ZomboidServerResource extends AbstractResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'ip' => config('zomboid.ip'),
            'port' => config('zomboid.port'),
            'status' => $this->status,
            'players' => [
                'list' => PlayerResource::collection($this->players),
            ],
        ];
    }
}
