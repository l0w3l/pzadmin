<?php

namespace App\Models\Game;

use App\Enums\Docker\ContainerStatusEnum;
use App\Enums\Models\Game\ServerEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Server extends Model
{
    /** @use HasFactory<Factory<Server>> */
    use HasFactory;

    protected $fillable = [
        'prefix',
        'name',
        'status',
    ];

    protected $casts = [
        'name' => ServerEnum::class,
        'status' => ContainerStatusEnum::class,
        'created_at' => 'immutable_datetime',
        'updated_at' => 'immutable_datetime',
    ];

    /**
     * @return Builder<Server>
     */
    public static function server(ServerEnum $enum): Builder
    {
        return Server::where('prefix', config('app.name'))->where('name', $enum->value);
    }

    /**
     * @return HasMany<Player>
     */
    public function players(): HasMany
    {
        return $this->hasMany(Player::class, 'world', 'name');
    }
}
