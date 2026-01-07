<?php

namespace App\Jobs\App\Zomboid\Logs;

use App\Events\App\Zomboid\Logs\UpdateConsoleEvent;
use App\Events\App\Zomboid\UpdateStatusEvent;
use App\Repositories\Game\Log\LogInstanceEnum;
use App\Services\Game\Log\LogServiceInterface;
use App\Services\Game\Zomboid\ZomboidServiceInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;

class ConsoleUpdateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const CACHE_KEY = 'app.zomboid.logs.console.md5';

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $logsService = App::make(LogServiceInterface::class);

        $oldMd5 = Cache::get(self::CACHE_KEY);
        $newMd5 = $logsService->getServerConsoleMD5();

        if ($oldMd5 !== $newMd5) {
            UpdateConsoleEvent::dispatch();

            Cache::set(self::CACHE_KEY, $newMd5);
        }
    }
}
