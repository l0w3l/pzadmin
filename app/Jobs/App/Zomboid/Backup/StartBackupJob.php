<?php

namespace App\Jobs\App\Zomboid\Backup;

use App\Exceptions\Services\Zomboid\Backup\FailedToCreateBackupException;
use App\Services\Zomboid\Backup\BackupServiceInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Lowel\Telepath\Exceptions\UpdateNotFoundInCurrentContextException;
use Lowel\Telepath\Facades\Extrasense;
use Lowel\Telepath\Facades\Paranormal;
use Phptg\BotApi\Type\Update\Update;

class StartBackupJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of seconds the job can run before timing out.
     */
    public int $timeout = 600;

    const BACKUP_CACHE_KEY = 'zomboid.backup';

    /**
     * Execute the job.
     *
     * @throws FailedToCreateBackupException
     * @throws BindingResolutionException
     */
    public function handle(): void
    {
        $this->setProgress(true);

        $backupService = App::make(BackupServiceInterface::class);

        try {
            $backupService->backup();
        } catch (FailedToCreateBackupException $e) {
            try {
                $update = Extrasense::update();
            } catch (UpdateNotFoundInCurrentContextException) {
                $update = new Update(-1);
            }

            Paranormal::catch($update, $e);

            throw $e;
        } finally {
            $this->setProgress(false);
        }
    }

    public static function inProgress(): bool
    {
        return Cache::has(self::BACKUP_CACHE_KEY);
    }

    protected function setProgress(bool $inProgress): void
    {
        if ($inProgress) {
            Cache::forever(self::BACKUP_CACHE_KEY, true);
        } else {
            Cache::delete(self::BACKUP_CACHE_KEY);
        }
    }
}
