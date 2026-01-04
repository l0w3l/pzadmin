<?php

declare(strict_types=1);

namespace App\Telegram\Keyboards\Inline\Zomboid\Buttons;

use Illuminate\Support\Facades\App;
use Lowel\Docker\ClientFactory as DockerClientFactory;
use Lowel\Telepath\Core\Router\Keyboard\Buttons\Inline\AbstractCallbackButton;
use Lowel\Telepath\Facades\SpiritBox;

class StartInlineButton extends AbstractCallbackButton
{
    public function handle(): callable
    {
        return function () {
            $dockerClientFactory = App::make(DockerClientFactory::class);
            $dockerClient = $dockerClientFactory->getClientWithHandler();

            SpiritBox::editMessageText(__('telepath.keyboards.zomboid.status.starting'));

            $dockerClient->containerStart(config('app.name').'_zomboid');

            sleep(5);

            (new RefreshInlineButton)->handle()();
        };
    }

    /**
     * @param string[] $args
     * @return int|string|callable
     */
    public function text(array $args = []): int|string|callable
    {
        return __('telepath.keyboards.zomboid.buttons.start');
    }
}
