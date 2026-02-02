<?php

namespace App\Jobs\Telegram;

use App\Telegram\Handlers\StartHandler;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Cache;
use Lowel\Telepath\Core\Router\Keyboard\KeyboardBuilderInterface;
use Lowel\Telepath\Facades\SpiritBox;
use Phptg\BotApi\FailResult;
use Phptg\BotApi\Type\LinkPreviewOptions;
use Phptg\BotApi\Type\Message;

class UpdateServerStatusJob implements ShouldQueue
{
    use Queueable;

    const CACHE_KEY = self::class;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        StartHandler::resolveMessageAndKeyboard(static function (string $newMessage, KeyboardBuilderInterface $keyboardBuilder) {
            $message = self::getMessage();

            if (! ($message instanceof Message)) {
                return;
            }

            self::setMessage(
                SpiritBox::editMessageText($newMessage, chatId: $message->chat->id, messageId: $message->messageId, linkPreviewOptions: new LinkPreviewOptions(true), replyMarkup: $keyboardBuilder)
            );
        });
    }

    public static function getMessage(): ?Message
    {
        return Cache::get(self::CACHE_KEY);
    }

    public static function setMessage(FailResult|Message $message): void
    {
        if ($message instanceof FailResult) {
            return;
        }

        Cache::forever(self::CACHE_KEY, $message);
    }

    public static function stop(): void
    {
        Cache::delete(self::CACHE_KEY);
    }
}
