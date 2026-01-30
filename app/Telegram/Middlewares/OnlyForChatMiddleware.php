<?php

declare(strict_types=1);

namespace App\Telegram\Middlewares;

use Lowel\Telepath\Core\Router\Middleware\AbstractTelegramMiddleware;
use Lowel\Telepath\Facades\Extrasense;

class OnlyForChatMiddleware extends AbstractTelegramMiddleware
{
    public function handler(): callable
    {
        return static function (callable $callback) {
            $chatId = config('telepath.chat_id');
            $userId = Extrasense::user()->id;

            if (Extrasense::chat()->id === $chatId || in_array($userId, Extrasense::profile()->whitelist)) {
                $callback();
            }
        };
    }
}
