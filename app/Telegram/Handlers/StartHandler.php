<?php

declare(strict_types=1);

namespace App\Telegram\Handlers;

use App\Telegram\Messages\StartMessageModel;
use Exception;
use Lowel\Telepath\Core\Router\Handler\AbstractTelegramHandler;

class StartHandler extends AbstractTelegramHandler
{
    /**
     * @throws Exception
     */
    public function handler(): callable
    {
        return static function () {
            StartMessageModel::send();
            StartMessageModel::enableLiveReload();
        };
    }
}
