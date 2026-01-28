<?php

declare(strict_types=1);

use App\Telegram\Handlers\StartHandler;
use App\Telegram\Keyboards\Inline\Zomboid\ZomboidInlineKeyboardFactory;
use App\Telegram\Middlewares\OnlyForChatMiddleware;
use Lowel\Telepath\Facades\Telepath;

Telepath::middleware(OnlyForChatMiddleware::class)->group(function () {
    Telepath::onCommand('start', StartHandler::class);

    Telepath::keyboard(ZomboidInlineKeyboardFactory::class);
});
