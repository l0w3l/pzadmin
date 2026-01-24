<?php

declare(strict_types=1);

namespace App\Telegram\Middlewares;

use Cache;
use Lowel\Telepath\Core\Router\Middleware\AbstractTelegramMiddleware;
use Lowel\Telepath\Facades\Extrasense;
use Phptg\BotApi\Type\Update\Update;

class OnlyForChatMiddleware extends AbstractTelegramMiddleware
{
    public function handler(): callable
    {
        return static function (callable $callback) {
            /** @var ?Update $update */
            $update = Cache::get('chat.registered');

            if ($update !== null && $update->message !== null) {
                $chatId = $update->message->chat->id ?? null;
                $threadId = $update->message->messageThreadId ?? null;

                if ($chatId === Extrasense::chat()->id && $threadId === Extrasense::message()->messageThreadId) {
                    $callback();

                    return;
                }
            }

            if (in_array(Extrasense::user()->id, Extrasense::profile()->whitelist)) {
                $callback();
            }
        };
    }
}
