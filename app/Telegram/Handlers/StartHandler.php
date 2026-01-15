<?php

declare(strict_types=1);

namespace App\Telegram\Handlers;

use App\Services\Docker\Enums\ContainerStatusEnum;
use App\Services\Steam\SteamServiceInterface;
use App\Services\Zomboid\Log\LogServiceInterface;
use App\Services\Zomboid\ZomboidServiceInterface;
use App\Telegram\Keyboards\Inline\Zomboid\ZomboidInlineKeyboardFactory;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\App;
use Lowel\Telepath\Core\Router\Handler\AbstractTelegramHandler;
use Lowel\Telepath\Core\Router\Keyboard\KeyboardBuilderInterface;
use Lowel\Telepath\Facades\Extrasense;
use Lowel\Telepath\Facades\SpiritBox;
use Phptg\BotApi\Type\LinkPreviewOptions;

class StartHandler extends AbstractTelegramHandler
{
    /**
     * @throws Exception
     */
    public function __invoke(): void
    {
        $this->lazyHandler(function (string $message, KeyboardBuilderInterface $keyboardBuilder) {
            $message = SpiritBox::sendMessage($message, parseMode: 'HTML', messageThreadId: Extrasense::message()->messageThreadId, linkPreviewOptions: new LinkPreviewOptions(true), replyMarkup: $keyboardBuilder);

            \Cache::forever('telepath.messages.start', $message);
        });
    }

    /**
     * @param  callable(string, KeyboardBuilderInterface):mixed  $lazyHandler
     *
     * @throws BindingResolutionException
     * @throws Exception
     */
    public function lazyHandler(callable $lazyHandler): void
    {
        $logsService = App::make(LogServiceInterface::class);
        $steamService = App::make(SteamServiceInterface::class);
        $zomboidService = App::make(ZomboidServiceInterface::class);

        $serverData = $zomboidService->getServer();

        if ($serverData->status === ContainerStatusEnum::DOWN) {
            $message = __('telepath.start.down');
            $keyboard = ZomboidInlineKeyboardFactory::isDead();
        } elseif ($serverData->status === ContainerStatusEnum::PENDING) {
            $message = __('telepath.start.pending');
            $keyboard = ZomboidInlineKeyboardFactory::isPending();
        } elseif ($serverData->status === ContainerStatusEnum::ACTIVE) {
            $playerDataCollection = $logsService->getPlayersInfo();
            $steamPlayers = $steamService->getPlayerSummariesForPlayerLogData(...$playerDataCollection);

            $players = '';
            foreach ($playerDataCollection as $index => $playerData) {
                $steamPlayer = $steamPlayers[$index];

                $players .= $playerData->toString($steamPlayer);
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

        $lazyHandler($message, $keyboard);
    }
}
