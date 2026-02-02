<?php

declare(strict_types=1);

namespace App\Telegram\Handlers;

use App\Exceptions\Services\Steam\SteamKeyNotFoundException;
use App\Jobs\Telegram\UpdateServerStatusJob;
use App\Services\Docker\Enums\ContainerStatusEnum;
use App\Services\Steam\SteamServiceFactory;
use App\Services\Zomboid\Log\LogServiceInterface;
use App\Services\Zomboid\ZomboidServiceInterface;
use App\Telegram\Keyboards\Inline\Zomboid\ZomboidInlineKeyboardFactory;
use Cache;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\App;
use Lowel\Telepath\Core\Router\Handler\AbstractTelegramHandler;
use Lowel\Telepath\Core\Router\Keyboard\KeyboardBuilderInterface;
use Lowel\Telepath\Facades\SpiritBox;
use Phptg\BotApi\Type\LinkPreviewOptions;

class StartHandler extends AbstractTelegramHandler
{
    /**
     * @throws Exception
     */
    public function handler(): callable
    {
        return static function () {
            self::resolveMessageAndKeyboard(
                fn (string $message, KeyboardBuilderInterface $keyboardBuilder) => UpdateServerStatusJob::setMessage(
                    SpiritBox::sendMessage(
                        $message,
                        parseMode: 'HTML',
                        linkPreviewOptions: new LinkPreviewOptions(true),
                        replyMarkup: $keyboardBuilder
                    )
                )
            );
        };
    }

    /**
     * @param  callable(string, KeyboardBuilderInterface):mixed  $resolver
     *
     * @throws BindingResolutionException
     * @throws Exception
     */
    public static function resolveMessageAndKeyboard(callable $resolver, bool $force = false): void
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

        $prevMessage = Cache::get('telegram.start');
        if ($prevMessage !== $message || $force) {
            Cache::forever('telegram.start', $message);

            $resolver($message, $keyboard);
        }
    }
}
