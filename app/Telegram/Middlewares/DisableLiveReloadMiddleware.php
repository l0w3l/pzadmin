<?php

declare(strict_types=1);

namespace App\Telegram\Middlewares;

use Cache;
use Lowel\Telepath\Core\Router\Middleware\AbstractTelegramMiddleware;

class DisableLiveReloadMiddleware extends AbstractTelegramMiddleware
{
    public function handler(): callable
    {
        return static function (callable $callback) {

            Cache::forget('telepath.messages.start');

            $callback();
        };
    }
}
