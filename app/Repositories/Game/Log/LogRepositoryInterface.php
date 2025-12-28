<?php

declare(strict_types=1);

namespace App\Repositories\Game\Log;

use App\Data\Game\LogData;
use App\Data\Game\LogInstanceData;
use App\Exceptions\Repositories\Game\LogInstance\LogInstanceNotFoundException;
use App\Repositories\Abstract\RepositoryInterface;
use Illuminate\Support\Collection;

interface LogRepositoryInterface extends RepositoryInterface
{
    /**
     * @return Collection<int, LogData>
     */
    public function all(): Collection;

    /**
     * @return Collection<int, LogData>
     */
    public function allByInstance(LogInstanceData $logInstanceData): Collection;

    /**
     * Save log instance data and return difference
     *
     * @param  Collection<int, LogData>  $logDataCollection
     * @return Collection<int, LogData> - new logs records that was saved
     */
    public function save(LogInstanceData $logInstanceData, Collection $logDataCollection): Collection;

    /**
     * Append logs into instance
     *
     * @param  Collection<int, LogData>  $logDataCollection
     * @return Collection<int, LogData>
     */
    public function append(LogInstanceData $logInstanceData, Collection $logDataCollection): Collection;

    /**
     * Reset all logs
     *
     * @throws LogInstanceNotFoundException
     */
    public function reset(LogInstanceData $logInstanceData): void;
}
