<?php

declare(strict_types=1);

namespace App\Telegram\Keyboards\Inline\Zomboid\Buttons\Confirmation;

use App\Telegram\Keyboards\Inline\Zomboid\ZomboidInlineKeyboardFactory;
use App\Telegram\Messages\StartMessageModel;
use Lowel\Telepath\Core\Router\Keyboard\Buttons\Inline\AbstractCallbackButton;

class FakeYesShutdownOptionInlineButton extends AbstractCallbackButton
{
    public function handle(): callable
    {
        return static function () {
            StartMessageModel::edit(__('telepath.keyboards.zomboid.questions.shutdown_sure'), ZomboidInlineKeyboardFactory::shutdownConfirmation());
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
