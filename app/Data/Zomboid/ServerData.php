<?php

namespace App\Data\Zomboid;

use App\Services\Docker\Enums\ContainerStatusEnum;
use Carbon\CarbonInterface;
use Carbon\CarbonInterval;
use Illuminate\Support\Carbon;
use Lowel\Docker\Response\DTO\Container;
use Spatie\LaravelData\Data;

final class ServerData extends Data
{
    public int $port;

    public string $ip;

    public function __construct(
        public ContainerStatusEnum $status,
        public CarbonInterface $startedAt,
        public CarbonInterval $uptime,
    ) {
        $this->port = (int) config('zomboid.port');
        $this->ip = config('zomboid.ip');
    }

    public static function fromInspectionResult(Container $serverInspection): self
    {
        $containerHealthStatus = $serverInspection->state['Health']['Status'] ?? null;
        $startedAt = Carbon::parse($serverInspection->state['StartedAt'] ?? null);
        $uptime = $startedAt->diffAsCarbonInterval(now());

        $statusEnum = ContainerStatusEnum::ERROR;
        if ($serverInspection->isDead() || $serverInspection->isStopped() || $serverInspection->isPaused()) {
            $statusEnum = ContainerStatusEnum::DOWN;
        } elseif ($serverInspection->isRestarting() || $containerHealthStatus !== 'healthy') {
            $statusEnum = ContainerStatusEnum::PENDING;
        } elseif ($serverInspection->isRunning()) {
            $statusEnum = ContainerStatusEnum::ACTIVE;
        }

        return new self(
            $statusEnum, $startedAt, $uptime
        );
    }
}
