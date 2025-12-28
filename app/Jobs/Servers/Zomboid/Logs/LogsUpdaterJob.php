<?php

declare(strict_types=1);

namespace App\Jobs\Servers\Zomboid\Logs;

use App\Events\Servers\Zomboid\Logs\RecordEvent as LogRecordEvent;
use App\Services\Game\Log\LogServiceInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class LogsUpdaterJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $logService = $this->getLogService();

        $logs = $logService->getServerConsoleLogsFromFilesystem();

        // return if nothing was changed
        if ($logService->getServerConsoleLogs()->count() === $logs->count()) {
            return;
        }

        $logService->saveLogsInDatabase($logs);

        event(new LogRecordEvent);
    }

    public function getLogService(): LogServiceInterface
    {
        return app(LogServiceInterface::class);
    }
}
