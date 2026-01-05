<?php

use App\Jobs\App\Zomboid\UpdateStatusJob as ZomboidUpdateStatusJob;
use App\Jobs\Telegram\UpdateStatusJob;
use Illuminate\Support\Facades\Schedule;

Schedule::job(UpdateStatusJob::class)->everyFiveSeconds();
Schedule::job(ZomboidUpdateStatusJob::class)->everyFiveSeconds();
