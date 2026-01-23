<?php

declare(strict_types=1);

namespace App\Data\Zomboid\Log;

use App\Data\Steam\GetPlayerSummaries\PlayerSummaryData;
use App\Models\Zomboid\Player;
use Illuminate\Support\Carbon;
use Spatie\LaravelData\Data;

class PlayerLogData extends Data
{
    public function __construct(
        public string $steamId,
        public string $name,
        public PlayerOnlineStatusEnum $online,
        public \DateTimeImmutable $updatedAt,
    ) {}

    public static function fromLogString(string $logString): self|false
    {
        $matches = [];

        preg_match('/\[(.+)] (\d+) "(.+)" allowed to join\.$/', $logString, $matches);

        $dateString = $matches[1] ?? null;
        $steamId = $matches[2] ?? null;
        $name = $matches[3] ?? null;

        if ($dateString !== null && $steamId !== null && $name !== null) {

            return new PlayerLogData($steamId, $name, PlayerOnlineStatusEnum::LOADING, \DateTimeImmutable::createFromFormat('d-m-y H:i:s.u', $dateString));
        } else {
            return false;
        }
    }

    public function higherThatUpdatedAt(string $dateString): bool
    {
        return $this->updatedAt < \DateTimeImmutable::createFromFormat('d-m-y H:i:s.u', $dateString);
    }

    public function setUpdatedAt(string $dateString): self
    {
        $this->updatedAt = \DateTimeImmutable::createFromFormat('d-m-y H:i:s.u', $dateString);

        return $this;
    }

    public function setOffline(): self
    {
        $this->online = PlayerOnlineStatusEnum::OFFLINE;

        return $this;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function setOnline(): self
    {
        $this->online = PlayerOnlineStatusEnum::ONLINE;

        return $this;
    }

    public function toStringByPlayerSummoryData(PlayerSummaryData $playerSummaryData): string
    {
        return match ($this->online) {
            PlayerOnlineStatusEnum::ONLINE => sprintf(
                '- %s <a href="%s">%s</a> (%s)'.PHP_EOL,
                $this->online->value, $playerSummaryData->profileurl, $playerSummaryData->personaname, Player::whereUsername($this->name)->first()->name ?? 'unknown'
            ),
            PlayerOnlineStatusEnum::LOADING => sprintf(
                '- %s <a href="%s">%s</a>'.PHP_EOL,
                $this->online->value, $playerSummaryData->profileurl, $playerSummaryData->personaname
            ),
            PlayerOnlineStatusEnum::OFFLINE => sprintf(
                '- %s <a href="%s">%s</a> (%s)'.PHP_EOL,
                $this->online->value, $playerSummaryData->profileurl, $playerSummaryData->personaname, Carbon::createFromInterface($this->updatedAt)->diffForHumans(['minimumUnit' => 'minutes'], short: true)
            ),
        };
    }

    public function toString(): string
    {
        return match ($this->online) {
            PlayerOnlineStatusEnum::ONLINE => sprintf(
                '- %s %s (%s)'.PHP_EOL,
                $this->online->value, $this->name, Player::whereUsername($this->name)->first()->name ?? 'unknown'
            ),
            PlayerOnlineStatusEnum::LOADING => sprintf(
                '- %s %s'.PHP_EOL,
                $this->online->value, $this->name
            ),
            PlayerOnlineStatusEnum::OFFLINE => sprintf(
                '- %s %s (%s)'.PHP_EOL,
                $this->online->value, $this->name, Carbon::createFromInterface($this->updatedAt)->diffForHumans(['minimumUnit' => 'minutes'], short: true)
            ),
        };
    }
}
