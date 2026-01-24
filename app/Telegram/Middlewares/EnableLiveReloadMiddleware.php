<?php

declare(strict_types=1);

namespace App\Telegram\Middlewares;

use Cache;
use Lowel\Telepath\Core\Router\Middleware\AbstractTelegramMiddleware;
use Lowel\Telepath\Facades\Extrasense;

class EnableLiveReloadMiddleware extends AbstractTelegramMiddleware
{
    public function handler(): callable
    {
        return static function (callable $callback) {
            $callback();

            Cache::forever('telepath.messages.start', Extrasense::message());
        };
    }
}
