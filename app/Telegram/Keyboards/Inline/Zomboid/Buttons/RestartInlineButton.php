<?php

declare(strict_types=1);

namespace App\Telegram\Keyboards\Inline\Zomboid\Buttons;

use App\Telegram\Keyboards\Inline\Zomboid\ZomboidInlineKeyboardFactory;
use Lowel\Telepath\Core\Router\Keyboard\Buttons\Inline\AbstractCallbackButton;
use Lowel\Telepath\Facades\SpiritBox;
use Phptg\BotApi\TelegramBotApi;
use Phptg\BotApi\Type\Chat;

class RestartInlineButton extends AbstractCallbackButton
{
    public function handle(): callable
    {
        return static function (TelegramBotApi $api, Chat $chat) {
            SpiritBox::editMessageText('Перезапустить сервер?', replyMarkup: ZomboidInlineKeyboardFactory::fakeYesRestartConfirmation());
        };
    }

    /**
     * @param  array<empty>  $args
     */
    public function text(array $args = []): int|string|callable
    {
        return __('telepath.keyboards.zomboid.buttons.restart');
    }
}
