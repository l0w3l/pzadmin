<?php

declare(strict_types=1);

namespace App\Telegram\Handlers;

use App\Enums\Docker\ContainerStatusEnum;
use App\Services\Game\Log\LogServiceInterface;
use App\Services\Game\Zomboid\ZomboidServiceInterface;
use App\Services\Steam\SteamServiceInterface;
use App\Telegram\Keyboards\Inline\Zomboid\ZomboidInlineKeyboardFactory;
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
    public function __invoke(): void
    {
        $this->lazyHandler(fn (string $message, KeyboardBuilderInterface $keyboardBuilder) => \Cache::forever('telepath.messages.start', SpiritBox::sendMessage($message, parseMode: 'HTML', linkPreviewOptions: new LinkPreviewOptions(true), replyMarkup: $keyboardBuilder)));
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

                $players .= sprintf(
                    '- %s <a href="%s">%s</a>'.PHP_EOL,
                    $playerData->online->value, $steamPlayer->profileurl, $steamPlayer->personaname
                );
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
