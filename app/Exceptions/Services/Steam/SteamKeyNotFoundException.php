<?php

namespace App\Exceptions\Services\Steam;

use App\Exceptions\CheckedException;

class SteamKeyNotFoundException extends CheckedException
{
    protected $message = 'STEAM_KEY not found in .env file';
}
