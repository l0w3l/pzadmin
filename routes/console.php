<?php

use App\Jobs\App\Zomboid\Logs\ConsoleUpdateJob;
use App\Jobs\App\Zomboid\UpdateStatusJob as ZomboidUpdateStatusJob;
use App\Jobs\Telegram\UpdateStatusJob;
use Illuminate\Support\Facades\Schedule;

Schedule::job(UpdateStatusJob::class)->everyTenSeconds();
// Schedule::job(ZomboidUpdateStatusJob::class)->everySecond();
// Schedule::job(ConsoleUpdateJob::class)->everySecond();
