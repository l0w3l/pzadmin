<?php

declare(strict_types=1);

namespace App\Telegram\Keyboards\Inline\Zomboid\Buttons;

use App\Telegram\Keyboards\Inline\Zomboid\ZomboidInlineKeyboardFactory;
use Illuminate\Support\Facades\App;
use Lowel\Docker\ClientFactory as DockerClientFactory;
use Lowel\Telepath\Core\Router\Keyboard\Buttons\Inline\AbstractCallbackButton;
use Lowel\Telepath\Facades\Extrasense;
use Lowel\Telepath\Facades\SpiritBox;
use Phptg\BotApi\TelegramBotApi;
use Phptg\BotApi\Type\Chat;

class StopInlineButton extends AbstractCallbackButton
{
    public function handle(): callable
    {
        return function () {
            SpiritBox::editMessageText("Выключить сервер?", chatId: Extrasense::chat()->id, messageId: Extrasense::message()->messageId, replyMarkup: ZomboidInlineKeyboardFactory::fakeYesShutdownConfirmation()->build());
        };
    }

    public function text(array $args = []): int|string|callable
    {
        return __('telepath.keyboards.zomboid.buttons.stop');
    }
}
