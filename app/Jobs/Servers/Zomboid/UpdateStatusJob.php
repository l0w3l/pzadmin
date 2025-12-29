<?php

namespace App\Jobs\Servers\Zomboid;

use App\Data\Game\ServerData;
use App\Events\Servers\Zomboid\StatusEvent as ZomboidStatusEvent;
use App\Services\Game\Zomboid\ZomboidServiceInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;

class UpdateStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $zomboidService = $this->getZomboidService();

        $zomboidService->updateStatus(
            fn (ServerData $serverData) => event(new ZomboidStatusEvent($serverData->status))
        );
    }

    private function getZomboidService(): ZomboidServiceInterface
    {
        return App::make(ZomboidServiceInterface::class);
    }
}
