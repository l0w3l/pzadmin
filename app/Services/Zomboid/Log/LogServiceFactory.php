<?php

declare(strict_types=1);

namespace App\Services\Zomboid\Log;

use App;
use Illuminate\Contracts\Container\BindingResolutionException;
use Lowel\LaravelServiceMaker\Services\ServiceFactoryInterface;

class LogServiceFactory implements ServiceFactoryInterface
{
    /**
     * @param  array<empty>  $params
     *
     * @throws BindingResolutionException
     */
    public function get(array $params = []): LogServiceInterface
    {
        return App::make(LogService::class);
    }
}
