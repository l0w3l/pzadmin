<?php

declare(strict_types=1);

namespace App\Services\Zomboid\Backup;

use Lowel\LaravelServiceMaker\Services\ServiceFactoryInterface;

class BackupServiceFactory implements ServiceFactoryInterface
{
    /**
     * @param  array<empty>  $params
     */
    public function get(array $params = []): BackupServiceInterface
    {
        return new BackupService;
    }
}
