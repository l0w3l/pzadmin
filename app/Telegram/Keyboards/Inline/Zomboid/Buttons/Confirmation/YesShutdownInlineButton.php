<?php

declare(strict_types=1);

namespace App\Telegram\Keyboards\Inline\Zomboid\Buttons\Confirmation;

use App\Telegram\Keyboards\Inline\Zomboid\Buttons\RefreshInlineButton;
use Illuminate\Support\Facades\App;
use Lowel\Docker\ClientFactory as DockerClientFactory;
use Lowel\Telepath\Core\Router\Keyboard\Buttons\Inline\AbstractCallbackButton;
use Lowel\Telepath\Facades\SpiritBox;

class YesShutdownInlineButton extends AbstractCallbackButton
{
    public function handle(): callable
    {
        return function () {
            $dockerClientFactory = App::make(DockerClientFactory::class);
            $dockerClient = $dockerClientFactory->getClientWithHandler();

            SpiritBox::editMessageText(__('telepath.keyboards.zomboid.status.stopping'));

            $dockerClient->containerStop(config('app.name').'_zomboid');

            (new RefreshInlineButton)->handle()();
        };
    }

    public function text(array $args = []): int|string|callable
    {
        return 'Да';
    }
}
