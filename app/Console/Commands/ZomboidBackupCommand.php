<?php

namespace App\Console\Commands;

use App\Jobs\App\Zomboid\Backup\StartBackupJob;
use App\Models\ZomboidBackup;
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
        $this->info('Start backup...');

        $timeStart = now();

        StartBackupJob::dispatchSync();

        $this->info('Backup created successfully. Time taken: '.now()->diff($timeStart)->forHumans());

        $this->info(ZomboidBackup::latest()->first()->file_path);
    }
}
