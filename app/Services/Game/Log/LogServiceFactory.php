<?php

declare(strict_types=1);

namespace App\Services\Game\Log;

use Lowel\LaravelServiceMaker\Services\ServiceFactoryInterface;

class LogServiceFactory implements ServiceFactoryInterface
{
    public function get(array $params = []): LogServiceInterface
    {
        return new LogService;
    }
}
