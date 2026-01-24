<?php

declare(strict_types=1);

namespace App\Telegram\Keyboards\Inline\Zomboid\Buttons\Confirmation;

use App\Telegram\Keyboards\Inline\Zomboid\Buttons\RefreshInlineButton;
use Lowel\Telepath\Core\Router\Keyboard\Buttons\Inline\AbstractCallbackButton;

class NoOptionInlineButton extends AbstractCallbackButton
{
    public function handle(): callable
    {
        return static function () {
            (new RefreshInlineButton)->handle()();
        };
    }

    /**
     * @param  array<empty>  $args
     */
    public function text(array $args = []): int|string|callable
    {
        return 'Нет';
    }
}
