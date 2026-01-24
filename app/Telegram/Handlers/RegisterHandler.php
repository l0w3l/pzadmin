<?php

declare(strict_types=1);

namespace App\Telegram\Handlers;

use Cache;
use Lowel\Telepath\Core\Router\Handler\AbstractTelegramHandler;
use Lowel\Telepath\Facades\SpiritBox;
use Phptg\BotApi\Type\Update\Update;

class RegisterHandler extends AbstractTelegramHandler
{
    public function handler(): callable
    {
        return static function (Update $update) {
            Cache::forever('chat.registered', $update);

            SpiritBox::deleteMessage();
        };
    }
}
