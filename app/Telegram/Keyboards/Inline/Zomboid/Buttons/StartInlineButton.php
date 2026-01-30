<?php

declare(strict_types=1);

namespace App\Telegram\Keyboards\Inline\Zomboid\Buttons;

use App\Services\Zomboid\ZomboidServiceInterface;
use Illuminate\Support\Facades\App;
use Lowel\Telepath\Core\Router\Keyboard\Buttons\Inline\AbstractCallbackButton;
use Lowel\Telepath\Facades\SpiritBox;

class StartInlineButton extends AbstractCallbackButton
{
    public function handle(): callable
    {
        return static function () {
            $zomboidService = App::make(ZomboidServiceInterface::class);

            SpiritBox::editMessageText(__('telepath.keyboards.zomboid.status.starting'));

            $zomboidService->doStart();

            (new RefreshInlineButton)->handle()();
        };
    }

    /**
     * @param  string[]  $args
     */
    public function text(array $args = []): int|string|callable
    {
        return __('telepath.keyboards.zomboid.buttons.start');
    }
}
