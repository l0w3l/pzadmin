<?php

declare(strict_types=1);

namespace App\Data\Zomboid\Log;

enum PlayerOnlineStatusEnum: string
{
    case ONLINE = '🟢';
    case OFFLINE = '🔴';
    case LOADING = '🟡';
}
