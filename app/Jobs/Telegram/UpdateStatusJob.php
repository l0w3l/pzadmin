<?php

namespace App\Jobs\Telegram;

use App\Telegram\Handlers\StartHandler;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Lowel\Telepath\Core\Router\Keyboard\KeyboardBuilderInterface;
use Lowel\Telepath\Facades\SpiritBox;
use Phptg\BotApi\Type\LinkPreviewOptions;
use Phptg\BotApi\Type\Message;

class UpdateStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        /** @var ?Message $messageData */
        $messageData = Cache::get('telepath.messages.start');

        if ($messageData !== null) {
            (new StartHandler)
                ->lazyHandler(fn (string $message, KeyboardBuilderInterface $keyboardBuilder) => SpiritBox::editMessageText(
                    $message,
                    chatId: $messageData->chat->id,
                    messageId: $messageData->messageId,
                    parseMode: 'HTML',
                    linkPreviewOptions: new LinkPreviewOptions(true),
                    replyMarkup: $keyboardBuilder
                ));
        }
    }
}
