<?php

declare(strict_types=1);

namespace App\Services\Zomboid\Backup;

use App\Exceptions\Services\Zomboid\Backup\FailedToCreateBackupException;
use Lowel\LaravelServiceMaker\Services\ServiceInterface;

interface BackupServiceInterface extends ServiceInterface
{
    /**
     * @throws FailedToCreateBackupException
     */
    public function backup(): false|string;

    public function configHash(): string;

    public function backupRequired(): bool;
}
