<?php

declare(strict_types=1);

namespace App\Telegram\Keyboards\Inline\Zomboid\Buttons\Confirmation;

use App\Telegram\Keyboards\Inline\Zomboid\ZomboidInlineKeyboardFactory;
use Lowel\Telepath\Core\Router\Keyboard\Buttons\Inline\AbstractCallbackButton;
use Lowel\Telepath\Facades\SpiritBox;

class FakeYesShutdownOptionInlineButton extends AbstractCallbackButton
{
    public function handle(): callable
    {
        return static function () {
            SpiritBox::editMessageText(
                'Ты УВЕРЕН, что хочешь выключить сервер?',
                replyMarkup: ZomboidInlineKeyboardFactory::shutdownConfirmation()
            );
        };
    }

    /**
     * @param  array<empty>  $args
     */
    public function text(array $args = []): int|string|callable
    {
        return 'Да';
    }
}
