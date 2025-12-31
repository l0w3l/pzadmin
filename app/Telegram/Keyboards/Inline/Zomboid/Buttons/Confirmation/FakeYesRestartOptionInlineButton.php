<?php

declare(strict_types=1);

namespace App\Telegram\Keyboards\Inline\Zomboid\Buttons\Confirmation;

use App\Telegram\Keyboards\Inline\Zomboid\ZomboidInlineKeyboardFactory;
use Lowel\Telepath\Core\Router\Keyboard\Buttons\Inline\AbstractCallbackButton;
use Lowel\Telepath\Facades\Extrasense;
use Lowel\Telepath\Facades\SpiritBox;

class FakeYesRestartOptionInlineButton extends AbstractCallbackButton
{
	function handle(): callable
	{
		return function() {
            SpiritBox::editMessageText("Ты УВЕРЕН, что хочешь перезапустить (!!!) сервер?", chatId: Extrasense::chat()->id, messageId: Extrasense::message()->messageId, replyMarkup: ZomboidInlineKeyboardFactory::restartConfirmation()->build());
		};
	}
	function text(array $args = []): int|string|callable
	{
		return 'Да';
	}
}
