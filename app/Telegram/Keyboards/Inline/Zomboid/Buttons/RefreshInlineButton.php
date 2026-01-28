<?php

declare(strict_types=1);

namespace App\Telegram\Keyboards\Inline\Zomboid\Buttons;

use App\Telegram\Messages\StartMessageModel;
use Lowel\Telepath\Core\Router\Keyboard\Buttons\Inline\AbstractCallbackButton;

class RefreshInlineButton extends AbstractCallbackButton
{
    public function handle(): callable
    {
        return static function () {
            StartMessageModel::edit(__('telepath.keyboards.zomboid.status.refreshing'));

            StartMessageModel::enableLiveReload();
            StartMessageModel::reload();
        };
    }

    /**
     * @param  array<empty>  $args
     */
    public function text(array $args = []): int|string|callable
    {
        return __('telepath.keyboards.zomboid.buttons.refresh');
    }
}
