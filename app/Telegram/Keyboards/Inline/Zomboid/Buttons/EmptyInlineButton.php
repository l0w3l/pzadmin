<?php

declare(strict_types=1);

namespace App\Telegram\Keyboards\Inline\Zomboid\Buttons;

use Lowel\Telepath\Core\Router\Keyboard\Buttons\Inline\AbstractCallbackButton;
use Lowel\Telepath\Core\Router\Keyboard\Buttons\Inline\AbstractInlineButton;

class EmptyInlineButton extends AbstractCallbackButton
{
    public function handle(): callable
    {
        return function () {

        };
    }

    public function text(array $args = []): int|string|callable
    {
        return '';
    }
}
