<?php

namespace App\Jobs\App\Zomboid;

use App\Events\App\Zomboid\UpdateStatusEvent;
use App\Services\Zomboid\ZomboidServiceInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;

class UpdateStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const CACHE_KEY = 'app.zomboid.status';

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $zomboidService = App::make(ZomboidServiceInterface::class);

        $serverStatus = $zomboidService->getServer();

        $cachedServerStatus = Cache::get(self::CACHE_KEY, null);

        if ($cachedServerStatus !== $serverStatus->status->value) {
            UpdateStatusEvent::dispatch($serverStatus->status);

            Cache::set(self::CACHE_KEY, $serverStatus->status->value);
        }
    }
}
