<?php

declare(strict_types=1);

namespace App\Services\Zomboid;

use App\Services\Docker\DockerServiceFactory;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\App;
use Lowel\LaravelServiceMaker\Services\ServiceFactoryInterface;

class ZomboidServiceFactory implements ServiceFactoryInterface
{
    /**
     * @param  array<empty>  $params
     *
     * @throws BindingResolutionException
     */
    public function get(array $params = []): ZomboidServiceInterface
    {
        return App::make(ZomboidService::class, [
            'zomboidDockerContainer' => (new DockerServiceFactory)->zomboid(),
        ]);
    }
}
