<?php

declare(strict_types=1);

namespace App\Telegram\Handlers;

use App\Data\Game\Log\PlayerLogData;
use App\Data\Game\Log\PlayerOnlineStatusEnum;
use App\Data\Game\PlayerData;
use App\Data\Steam\GetPlayerSummaries\PlayerSummaryData;
use App\Models\Game\Player;
use App\Services\Game\Log\LogServiceInterface;
use App\Services\Steam\SteamServiceInterface;
use App\Telegram\Keyboards\Inline\Zomboid\ZomboidInlineKeyboardFactory;
use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Lowel\Docker\ClientFactory as DockerClientFactory;
use Lowel\Telepath\Core\Router\Handler\AbstractTelegramHandler;
use Lowel\Telepath\Exceptions\ChatNotFoundInCurrentContextException;
use Lowel\Telepath\Exceptions\UpdateNotFoundInCurrentContextException;
use Lowel\Telepath\Facades\Extrasense;
use Lowel\Telepath\Facades\SpiritBox;
use Phptg\BotApi\Type\LinkPreviewOptions;

class StartHandler extends AbstractTelegramHandler
{
    /**
     * @throws ChatNotFoundInCurrentContextException
     * @throws BindingResolutionException
     * @throws UpdateNotFoundInCurrentContextException
     * @throws Exception
     */
    public function __invoke(): void
    {
        $logsService = App::make(LogServiceInterface::class);
        $steamService = App::make(SteamServiceInterface::class);
        $dockerClientFactory = App::make(DockerClientFactory::class);
        $dockerClient = $dockerClientFactory->getClientWithHandler();

        $containerInspectResult = $dockerClient->containerInspect(config('app.name').'_zomboid');
        $containerHealthStatus = $containerInspectResult->state['Health']['Status'] ?? null;

        $chatId = Extrasense::chat()->id;

        if ($containerInspectResult->isDead() || $containerInspectResult->isStopped() || $containerInspectResult->isPaused()) {
            $message = __('telepath.start.down');
            $keyboard = ZomboidInlineKeyboardFactory::isDead()->build();
        } elseif ($containerInspectResult->isRestarting() || $containerHealthStatus !== 'healthy') {
            $message = __('telepath.start.pending');
            $keyboard = ZomboidInlineKeyboardFactory::isPending()->copy([...ZomboidInlineKeyboardFactory::isPending()->toArray(), ...ZomboidInlineKeyboardFactory::nothing()->toArray()])->build();;
        } elseif ($containerInspectResult->isRunning() && $containerHealthStatus === 'healthy') {
            $uptime = Carbon::parse($containerInspectResult->state['StartedAt'])->diffAsCarbonInterval(now())->forHumans();
            $logPlayers = $logsService->getPlayersInfo();
            $steamPlayers = $steamService->getPlayerSummaries(array_map(fn (PlayerLogData $playerLogData) => $playerLogData->steamId, $logPlayers));

            usort($logPlayers, fn (PlayerLogData $playerLogData) => match ($playerLogData->online) {
                PlayerOnlineStatusEnum::ONLINE => -1,
                PlayerOnlineStatusEnum::LOADING => 0,
                PlayerOnlineStatusEnum::OFFLINE => 1,
            });


            $players = "";
            foreach ($logPlayers as $logPlayer) {
                foreach ($steamPlayers as $steamPlayer) {
                    if ($steamPlayer->steamid === $logPlayer->steamId) {
                        $players .= sprintf('- %s <a href="%s">%s</a>'.PHP_EOL, $logPlayer->online->value, $steamPlayer->profileurl, $steamPlayer->personaname);
                    }
                }
            }

            if ($players !== "") {
                $players = "\n{$players}";
            }

            $message = __('telepath.start.active', ['time' => $uptime, 'players' => $players]);
            $keyboard = ZomboidInlineKeyboardFactory::isActive()->build();
        } else {
            $message = __('telepath.start.unknown');
            $keyboard = ZomboidInlineKeyboardFactory::isDead()->copy([...ZomboidInlineKeyboardFactory::isDead()->toArray(), ...ZomboidInlineKeyboardFactory::nothing()->toArray()])->build();
        }

        SpiritBox::sendMessage($chatId, $message, replyMarkup: $keyboard, parseMode: "HTML", linkPreviewOptions: new LinkPreviewOptions(true));
    }
}
