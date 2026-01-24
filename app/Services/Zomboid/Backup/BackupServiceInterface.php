<?php

declare(strict_types=1);

namespace App\Services\Zomboid\Backup;

use Lowel\LaravelServiceMaker\Services\ServiceInterface;

interface BackupServiceInterface extends ServiceInterface
{
    const BACKUP_CACHE_KEY = 'zomboid.backup';

    public function backup(): false|string;

    public function configHash(): string;

    public function inProgress(): bool;
}
