<?php

declare(strict_types=1);

namespace App\Services\Game\Log;

use App\Data\Game\LogData;
use Illuminate\Support\Collection;

interface LogServiceInterface
{
    /**
     * Return server console logs from DB
     *
     * @return Collection<int, LogData>
     */
    public function getServerConsoleLogs(): Collection;

    /**
     * Return server console logs from filesystem
     *
     * @return Collection<int, LogData>
     */
    public function getServerConsoleLogsFromFilesystem(): Collection;

    /**
     * @param  Collection<int, LogData>  $logsInstancesData
     * @return Collection<int, LogData>
     */
    public function saveLogsInDatabase(Collection $logsInstancesData): Collection;

    /**
     * Append logs into database
     *
     * @param Collection<int, LogData> $logsInstanceData
     * @return Collection<int, LogData>
     */
    public function appendLogsInDatabase(Collection $logsInstanceData): Collection;

    /**
     * Reset all logs
     */
    public function resetLogsInDatabase(): void;
}
