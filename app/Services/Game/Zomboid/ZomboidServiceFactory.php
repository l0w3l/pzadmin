<?php

declare(strict_types=1);

namespace App\Services\Game\Zomboid;

use App\Enums\Models\Game\ServerEnum;
use App\Services\Abstract\ServiceFactoryInterface;
use App\Services\Game\Zomboid\Docker\ZomboidDockerContainer;
use Illuminate\Support\Facades\App;
use Lowel\Docker\ClientFactory as DockerClientFactory;

class ZomboidServiceFactory implements ServiceFactoryInterface
{
    public function get(): ZomboidServiceInterface
    {
        $dockerClientFactory = App::make(DockerClientFactory::class);

        return App::make(ZomboidService::class, [
            'zomboidDockerContainer' => new ZomboidDockerContainer($dockerClientFactory->getClientWithHandler(), ServerEnum::ZOMBOID->name()),
        ]);
    }
}
