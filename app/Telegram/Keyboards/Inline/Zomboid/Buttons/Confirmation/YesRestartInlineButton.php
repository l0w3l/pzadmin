<?php

declare(strict_types=1);

namespace App\Telegram\Keyboards\Inline\Zomboid\Buttons\Confirmation;

use App\Telegram\Keyboards\Inline\Zomboid\Buttons\RefreshInlineButton;
use App\Telegram\Keyboards\Inline\Zomboid\ZomboidInlineKeyboardFactory;
use Illuminate\Support\Facades\App;
use Lowel\Docker\ClientFactory as DockerClientFactory;
use Lowel\Telepath\Core\Router\Keyboard\Buttons\Inline\AbstractCallbackButton;
use Lowel\Telepath\Facades\Extrasense;
use Lowel\Telepath\Facades\SpiritBox;

class YesRestartInlineButton extends AbstractCallbackButton
{
    public function handle(): callable
    {
        return function () {
            $dockerClientFactory = App::make(DockerClientFactory::class);
            $dockerClient = $dockerClientFactory->getClientWithHandler();

            $chatId = Extrasense::chat()->id;
            $messageId = Extrasense::message()->messageId;

            SpiritBox::editMessageText(__('telepath.keyboards.zomboid.status.restarting'), chatId: $chatId, messageId: $messageId, replyMarkup: ZomboidInlineKeyboardFactory::nothing()->build());

            $dockerClient->containerStop(config('app.name').'_zomboid');
            $dockerClient->containerStart(config('app.name').'_zomboid');

            (new RefreshInlineButton)->handle()();
        };
    }

    public function text(array $args = []): int|string|callable
    {
        return 'Да';
    }
}
