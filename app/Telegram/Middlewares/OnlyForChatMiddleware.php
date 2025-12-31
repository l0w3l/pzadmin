<?php

declare(strict_types=1);

namespace App\Telegram\Middlewares;

use Lowel\Telepath\Core\Router\Middleware\AbstractTelegramMiddleware;
use Lowel\Telepath\Facades\Extrasense;

class OnlyForChatMiddleware extends AbstractTelegramMiddleware
{
    public function __invoke(callable $callback): void
    {
        if (Extrasense::chat()->id === config('telepath.chat_id')) {
            $callback();
        }
    }
}
