<?php

use App\Jobs\Telegram\UpdateStatusJob;
use Illuminate\Support\Facades\Schedule;

Schedule::job(UpdateStatusJob::class)->everyFiveMinutes();
