<?php

declare(strict_types=1);

namespace App\Telegram\Middlewares;

use Cache;
use Lowel\Telepath\Core\Router\Middleware\AbstractTelegramMiddleware;
use Phptg\BotApi\TelegramBotApi;
use Phptg\BotApi\Type\Update\Update;

class DisableLiveReloadMiddleware extends AbstractTelegramMiddleware
{
    public function __invoke(TelegramBotApi $telegramBotApi, Update $update, callable $callback): void
    {
        Cache::forget('telepath.messages.start');

        $callback();
    }
}
