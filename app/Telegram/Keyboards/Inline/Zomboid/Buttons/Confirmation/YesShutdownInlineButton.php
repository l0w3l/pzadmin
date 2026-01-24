<?php

declare(strict_types=1);

namespace App\Telegram\Keyboards\Inline\Zomboid\Buttons\Confirmation;

use App\Services\Zomboid\ZomboidServiceInterface;
use App\Telegram\Keyboards\Inline\Zomboid\Buttons\RefreshInlineButton;
use Illuminate\Support\Facades\App;
use Lowel\Telepath\Core\Router\Keyboard\Buttons\Inline\AbstractCallbackButton;
use Lowel\Telepath\Facades\SpiritBox;

class YesShutdownInlineButton extends AbstractCallbackButton
{
    public function handle(): callable
    {
        return static function () {
            $zomboidService = App::make(ZomboidServiceInterface::class);

            SpiritBox::editMessageText(__('telepath.keyboards.zomboid.status.stopping'));

            $zomboidService->doStop();

            (new RefreshInlineButton)->handle()();
        };
    }

    /**
     * @param  array<empty>  $args
     */
    public function text(array $args = []): int|string|callable
    {
        return 'Да';
    }
}
