<?php

declare(strict_types=1);

namespace App\Telegram\Middlewares;

use Cache;
use Lowel\Telepath\Core\Router\Middleware\AbstractTelegramMiddleware;
use Lowel\Telepath\Facades\Extrasense;
use Phptg\BotApi\TelegramBotApi;
use Phptg\BotApi\Type\Update\Update;

class EnableLiveReloadMiddleware extends AbstractTelegramMiddleware
{
    public function __invoke(TelegramBotApi $telegramBotApi, Update $update, callable $callback): void
    {
        $callback();

        Cache::forever('telepath.messages.start', Extrasense::message());
    }
}
