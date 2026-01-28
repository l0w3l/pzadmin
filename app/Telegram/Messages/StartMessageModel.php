<?php

declare(strict_types=1);

namespace App\Telegram\Messages;

use App\Exceptions\Services\Steam\SteamKeyNotFoundException;
use App\Services\Docker\Enums\ContainerStatusEnum;
use App\Services\Steam\SteamServiceFactory;
use App\Services\Zomboid\Log\LogServiceInterface;
use App\Services\Zomboid\ZomboidServiceInterface;
use App\Telegram\Keyboards\Inline\Zomboid\ZomboidInlineKeyboardFactory;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Lowel\Telepath\Core\Router\Keyboard\KeyboardBuilderInterface;
use Lowel\Telepath\Facades\Extrasense;
use Lowel\Telepath\Facades\SpiritBox;
use Phptg\BotApi\Type\LinkPreviewOptions;
use Phptg\BotApi\Type\Message;

class StartMessageModel
{
    public static function send(): void
    {
        self::resolveMessageAndKeyboard(function (string $messageText, KeyboardBuilderInterface $keyboardBuilder): void {
            $message = SpiritBox::sendMessage(
                $messageText,
                messageThreadId: Extrasense::message()->messageThreadId,
                parseMode: 'HTML',
                linkPreviewOptions: new LinkPreviewOptions(true),
                replyMarkup: $keyboardBuilder
            );

            self::setMessage($message);
        });
    }

    public static function edit(?string $text = null, ?KeyboardBuilderInterface $keyboardBuilder = null): void
    {
        $message = self::getMessage();

        if ($message === null) {
            return;
        }

        if ($text !== null && $message->text !== $text) {
            self::stopLiveReload();

            $newMessage = SpiritBox::editMessageText($text, chatId: $message->chat->id, messageId: $message->messageId, replyMarkup: $keyboardBuilder);

            self::setMessage($newMessage);
        }
    }

    public static function reload(): void
    {
        if (! self::isLiveReload()) {
            return;
        }

        $message = self::getMessage();

        if ($message === null) {
            return;
        }

        self::resolveMessageAndKeyboard(function (string $messageText, KeyboardBuilderInterface $keyboardBuilder) use ($message): void {
            if ($message->text === $messageText) {
                return;
            }

            $newMessage = SpiritBox::editMessageText(
                $messageText,
                chatId: $message->chat->id,
                messageId: $message->messageId,
                parseMode: 'HTML',
                linkPreviewOptions: new LinkPreviewOptions(true),
                replyMarkup: $keyboardBuilder
            );

            self::setMessage($newMessage);
        });
    }

    public static function stopLiveReload(): void
    {
        Cache::forever('telepath.messages.start.live', false);
    }

    public static function enableLiveReload(): void
    {
        Cache::forever('telepath.messages.start.live', true);
    }

    public static function isLiveReload(): bool
    {
        return Cache::get('telepath.messages.start.live', true);
    }

    protected static function setMessage(Message $message): void
    {
        Cache::forever('telepath.messages.start.message', $message);
    }

    public static function getMessage(): ?Message
    {
        /** @var ?Message $message */
        $message = Cache::get('telepath.messages.start.message');

        return $message;
    }

    /**
     * @param  callable(string, KeyboardBuilderInterface):mixed  $resolver
     *
     * @throws BindingResolutionException
     * @throws Exception
     */
    protected static function resolveMessageAndKeyboard(callable $resolver): void
    {
        $logsService = App::make(LogServiceInterface::class);
        $zomboidService = App::make(ZomboidServiceInterface::class);

        $serverData = $zomboidService->getServer();

        if ($serverData->status === ContainerStatusEnum::BACKUP) {
            $message = __('telepath.start.backup');
            $keyboard = ZomboidInlineKeyboardFactory::backup();
        } elseif ($serverData->status === ContainerStatusEnum::DOWN) {
            $message = __('telepath.start.down');
            $keyboard = ZomboidInlineKeyboardFactory::isDead();
        } elseif ($serverData->status === ContainerStatusEnum::PENDING) {
            $message = __('telepath.start.pending');
            $keyboard = ZomboidInlineKeyboardFactory::isPending();
        } elseif ($serverData->status === ContainerStatusEnum::ACTIVE) {
            $playerDataCollection = $logsService->getPlayersInfo();

            try {
                $steamPlayers = App::make(SteamServiceFactory::class)->get()->getPlayerSummariesForPlayerLogData(...$playerDataCollection);

                $players = '';
                foreach ($playerDataCollection as $index => $playerData) {
                    $steamPlayer = $steamPlayers[$index];

                    $players .= $playerData->toStringByPlayerSummoryData($steamPlayer);
                }
            } catch (SteamKeyNotFoundException $e) {
                $players = '';
                foreach ($playerDataCollection as $playerData) {

                    $players .= $playerData->toString();
                }
            }

            $message = __('telepath.start.active', [
                'time' => $serverData->uptime->forHumans(short: true),
                'ip' => $serverData->ip,
                'port' => $serverData->port,
                'players' => $players,
            ]);
            $keyboard = ZomboidInlineKeyboardFactory::isActive();
        } else {
            $message = __('telepath.start.unknown');
            $keyboard = ZomboidInlineKeyboardFactory::isDead();
        }

        $resolver($message, $keyboard);
    }
}
