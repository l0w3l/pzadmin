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
use Illuminate\Support\Facades\Cache;
use Lowel\Docker\ClientFactory as DockerClientFactory;
use Lowel\Telepath\Facades\Extrasense;
use Lowel\Telepath\Facades\SpiritBox;

class UpdateStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $dockerClientFactory = App::make(DockerClientFactory::class);
        $dockerClient = $dockerClientFactory->getClientWithHandler();

        $containerInspectResult = $dockerClient->containerInspect(config('app.name').'_zomboid');
        $containerHealthStatus = $containerInspectResult->state['Health']['Status'] ?? null;

        $flag = Cache::get('server_status_active', false);

        if (false === $flag && $containerInspectResult->isRunning() && $containerHealthStatus === 'healthy') {
            SpiritBox::sendMessage(config('telepath.chat_id'), __('telepath.start.active'));
            Cache::forever('server_status_active', true);
        } elseif (true === $flag) {
            SpiritBox::sendMessage(config('telepath.chat_id'), __('telepath.start.down'));
            Cache::forever('server_status_active', false);
        }

    }

    private function getZomboidService(): ZomboidServiceInterface
    {
        return App::make(ZomboidServiceInterface::class);
    }
}
