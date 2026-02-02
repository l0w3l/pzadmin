<?php

declare(strict_types=1);

namespace App\Telegram\Keyboards\Inline\Zomboid\Buttons;

use App\Jobs\Telegram\UpdateServerStatusJob;
use App\Telegram\Handlers\StartHandler;
use Lowel\Telepath\Core\Router\Keyboard\Buttons\Inline\AbstractCallbackButton;
use Lowel\Telepath\Core\Router\Keyboard\KeyboardBuilderInterface;
use Lowel\Telepath\Facades\SpiritBox;
use Phptg\BotApi\Type\LinkPreviewOptions;

class RefreshInlineButton extends AbstractCallbackButton
{
    public function handle(): callable
    {
        return static function () {
            SpiritBox::editMessageText(__('telepath.keyboards.zomboid.status.refreshing'));

            StartHandler::resolveMessageAndKeyboard(
                fn (string $message, KeyboardBuilderInterface $keyboardBuilder) => UpdateServerStatusJob::setMessage(SpiritBox::editMessageText(
                    $message,
                    parseMode: 'HTML',
                    linkPreviewOptions: new LinkPreviewOptions(true),
                    replyMarkup: $keyboardBuilder
                ))
            );
        };
    }

    /**
     * @param  array<empty>  $args
     */
    public function text(array $args = []): int|string|callable
    {
        return __('telepath.keyboards.zomboid.buttons.refresh');
    }
}
