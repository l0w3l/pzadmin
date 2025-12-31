<?php

declare(strict_types=1);

namespace App\Telegram\Keyboards\Inline\Zomboid\Buttons;

use Illuminate\Support\Facades\App;
use Lowel\Docker\ClientFactory as DockerClientFactory;
use Lowel\Telepath\Core\Router\Keyboard\Buttons\Inline\AbstractCallbackButton;
use Lowel\Telepath\Facades\Extrasense;
use Lowel\Telepath\Facades\SpiritBox;

class StartInlineButton extends AbstractCallbackButton
{
    public function handle(): callable
    {
        return function () {
            $dockerClientFactory = App::make(DockerClientFactory::class);
            $dockerClient = $dockerClientFactory->getClientWithHandler();

            $chatId = Extrasense::chat()->id;
            $messageId = Extrasense::message()->messageId;

            SpiritBox::editMessageText(__('telepath.keyboards.zomboid.status.starting'), chatId: $chatId, messageId: $messageId);

            $dockerClient->containerStart(config('app.name').'_zomboid');

            (new RefreshInlineButton)->handle()();
        };
    }

    public function text(array $args = []): int|string|callable
    {
        return __('telepath.keyboards.zomboid.buttons.start');
    }
}
