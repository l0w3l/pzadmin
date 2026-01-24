<?php

namespace App\Data\Zomboid;

use App\Services\Docker\Enums\ContainerStatusEnum;
use App\Services\Zomboid\Backup\BackupServiceInterface;
use Carbon\CarbonInterface;
use Carbon\CarbonInterval;
use Illuminate\Support\Carbon;
use Lowel\Docker\Response\DTO\Inspect\Container;
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
        $startedAt = Carbon::parse($serverInspection->State->StartedAt);
        $uptime = $startedAt->diffAsCarbonInterval(now());

        $statusEnum = ContainerStatusEnum::ERROR;
        if (app()->make(BackupServiceInterface::class)->inProgress()) {
            $statusEnum = ContainerStatusEnum::BACKUP;
        } else if ($serverInspection->State->Paused || $serverInspection->State->Dead || $serverInspection->State->OOMKilled || $serverInspection->State->ExitCode !== 0) {
            $statusEnum = ContainerStatusEnum::DOWN;
        } elseif ($serverInspection->State->Restarting || $serverInspection->State->Health->Status !== 'healthy') {
            $statusEnum = ContainerStatusEnum::PENDING;
        } elseif ($serverInspection->State->Running) {
            $statusEnum = ContainerStatusEnum::ACTIVE;
        }

        return new self(
            $statusEnum, $startedAt, $uptime
        );
    }
}
