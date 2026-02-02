<?php

use App\Jobs\App\Zomboid\Logs\ConsoleUpdateJob;
use App\Jobs\App\Zomboid\UpdateStatusJob as ZomboidUpdateStatusJob;
use App\Jobs\Telegram\UpdateServerStatusJob;
use Illuminate\Support\Facades\Schedule;

Schedule::job(ZomboidUpdateStatusJob::class)->everySecond();
Schedule::job(ConsoleUpdateJob::class)->everySecond();
Schedule::job(UpdateServerStatusJob::class)->everyFiveSeconds();
