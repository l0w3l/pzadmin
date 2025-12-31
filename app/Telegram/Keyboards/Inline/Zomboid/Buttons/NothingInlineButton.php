<?php

declare(strict_types=1);

namespace App\Telegram\Keyboards\Inline\Zomboid\Buttons;

use App\Telegram\Keyboards\Inline\Zomboid\ZomboidInlineKeyboardFactory;
use Lowel\Telepath\Core\Router\Keyboard\Buttons\Inline\AbstractCallbackButton;
use Lowel\Telepath\Facades\Extrasense;
use Lowel\Telepath\Facades\SpiritBox;

class NothingInlineButton extends AbstractCallbackButton
{
	function handle(): callable
	{
		return function() {
            SpiritBox::answerCallbackQuery(Extrasense::update()->callbackQuery->id, "ничего", true);
		};
	}
	function text(array $args = []): int|string|callable
	{
		return 'ничего не делать';
	}
}
