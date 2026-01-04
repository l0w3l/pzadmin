<?php

declare(strict_types=1);

namespace App\Data\Game\Log;

enum PlayerOnlineStatusEnum: string
{
    case ONLINE = '🟢';
    case OFFLINE = '🔴';
    case LOADING = '🟡';
}
