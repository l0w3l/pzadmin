<?php

declare(strict_types=1);

namespace App\Telegram\Keyboards\Inline\Zomboid\Buttons;

use App\Telegram\Keyboards\Inline\Zomboid\ZomboidInlineKeyboardFactory;
use Lowel\Telepath\Core\Router\Keyboard\Buttons\Inline\AbstractCallbackButton;
use Lowel\Telepath\Facades\Extrasense;
use Lowel\Telepath\Facades\SpiritBox;

class CasinoInlineButton extends AbstractCallbackButton
{
	function handle(): callable
	{
		return function() {
            $message = SpiritBox::sendDice(Extrasense::chat()->id, emoji: "🎰");

            $messageText = match($message->dice->value) {
                1 => "молодец молодец",
                22 => "СЛИВЫ",
                43 => "СКОЛЬКО НАХУЙ?",
                64 => "Я ПОПАЛ В ЕБАНЫЙ ДЖЕКПОТ МНЕ ПИЗДА",
                default => "лохъ"
            };

            $originalMessageTextImplode = explode("\n", Extrasense::message()->text);

            $originalHeader = $originalMessageTextImplode[0];
            $casinoList = $originalMessageTextImplode[1] ?? "";

            $from = Extrasense::update()->callbackQuery->from;
            $fromName = trim("{$from->firstName} {$from->lastName}");

            sleep(5);

            if (!empty($casinoList) and str_contains($casinoList, $fromName)) {
                $formattedList = [];
                $players = explode("\n-", $casinoList);

                foreach ($players as $player) {
                    [$name, $result] = explode(" ", $player);

                    if ($fromName === $name) {
                        $result = $messageText;
                    }

                    $formattedList[] = "{$name} {$result}";
                }

                $casinoList = implode("\n-", $formattedList);
            } else {
                $casinoList .= "\n- {$fromName} $messageText";
            }

            SpiritBox::editMessageText("{$originalHeader}\n{$casinoList}", chatId: Extrasense::chat()->id, messageId: Extrasense::message()->messageId, replyMarkup: Extrasense::message()->replyMarkup);

            dump(SpiritBox::deleteMessage(Extrasense::chat()->id, $message->messageId));
		};
	}
	function text(array $args = []): int|string|callable
	{
		return 'казик 🎰';
	}
}
