<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ZomboidBackupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:zomboid-backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a backup of the Zomboid server data';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $backupService = app()->make(\App\Services\Zomboid\Backup\BackupServiceInterface::class);

        $backupFile = $backupService->backup();

        $this->info('Backup created successfully.');
        $this->info($backupFile);
    }
}
