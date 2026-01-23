<?php

declare(strict_types=1);

namespace App\Repositories\Zomboid\Log;

use Lowel\LaravelServiceMaker\Repositories\RepositoryFactoryInterface;

class LogRepositoryFactory implements RepositoryFactoryInterface
{
    public function get(): LogRepositoryInterface
    {
        return new LogRepository;
    }
}
