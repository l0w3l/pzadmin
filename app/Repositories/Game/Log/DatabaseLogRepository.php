<?php

declare(strict_types=1);

namespace App\Repositories\Game\Log;

use App\Data\Game\LogData;
use App\Data\Game\LogInstanceData;
use App\Events\Servers\Zomboid\Logs\RecordEvent as LogRecordEvent;
use App\Exceptions\Repositories\Game\LogInstance\LogInstanceNotFoundException;
use App\Models\Game\Log;
use App\Models\Game\LogInstance;
use App\Repositories\Abstract\AbstractRepository;
use Illuminate\Support\Collection;

class DatabaseLogRepository extends AbstractRepository implements LogRepositoryInterface
{
    public function all(): Collection
    {
        $logs = Log::with('logInstance')->get();

        return LogData::collect($logs);
    }

    public function allByInstance(LogInstanceData $logInstanceData): Collection
    {
        $logs = Log::with('logInstance')->where('log_instance_id', $logInstanceData->id)->get();

        return LogData::collect($logs);
    }

    /**
     * @throws LogInstanceNotFoundException
     */
    public function save(LogInstanceData $logInstanceData, Collection $logDataCollection): Collection
    {
        $logInstance = LogInstance::fromLogInstanceData($logInstanceData);

        $logInstance->logs()->delete();

        $logInstance->logs()->saveMany(
            $logDataCollection->map(fn (LogData $logData) => new Log($logData->toArray()))->all()
        );

        return LogData::collect($logInstance->logs);
    }

    /**
     * @throws LogInstanceNotFoundException
     */
    public function append(LogInstanceData $logInstanceData, Collection $logDataCollection): Collection
    {
        $logInstance = LogInstance::fromLogInstanceData($logInstanceData);

        $logInstance->logs()->saveMany(
            $logDataCollection->map(fn (LogData $logData) => new Log($logData->toArray()))->all()
        );

        return LogData::collect($logInstance->logs);
    }

    public function reset(LogInstanceData $logInstanceData): void
    {
        $logInstance = LogInstance::fromLogInstanceData($logInstanceData);

        $logInstance->logs()->delete();

        event(new LogRecordEvent);
    }
}
