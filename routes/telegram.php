<?php

declare(strict_types=1);

use App\Telegram\Handlers\RegisterHandler;
use App\Telegram\Handlers\StartHandler;
use App\Telegram\Keyboards\Inline\Zomboid\Buttons\Confirmation\FakeYesRestartOptionInlineButton;
use App\Telegram\Keyboards\Inline\Zomboid\Buttons\Confirmation\FakeYesShutdownOptionInlineButton;
use App\Telegram\Keyboards\Inline\Zomboid\Buttons\Confirmation\NoOptionInlineButton;
use App\Telegram\Keyboards\Inline\Zomboid\Buttons\Confirmation\YesRestartInlineButton;
use App\Telegram\Keyboards\Inline\Zomboid\Buttons\Confirmation\YesShutdownInlineButton;
use App\Telegram\Keyboards\Inline\Zomboid\Buttons\RefreshInlineButton;
use App\Telegram\Keyboards\Inline\Zomboid\Buttons\RestartInlineButton;
use App\Telegram\Keyboards\Inline\Zomboid\Buttons\StartInlineButton;
use App\Telegram\Keyboards\Inline\Zomboid\Buttons\StopInlineButton;
use App\Telegram\Middlewares\DisableLiveReloadMiddleware;
use App\Telegram\Middlewares\EnableLiveReloadMiddleware;
use App\Telegram\Middlewares\OnlyForChatMiddleware;
use Lowel\Telepath\Facades\Telepath;

Telepath::middleware(OnlyForChatMiddleware::class)->group(function () {
    Telepath::onCommand('start', StartHandler::class);
    Telepath::onCommand('register', RegisterHandler::class);

    Telepath::buttons(
        new StopInlineButton, new RestartInlineButton, new FakeYesRestartOptionInlineButton, new FakeYesShutdownOptionInlineButton
    )->middleware(DisableLiveReloadMiddleware::class);

    Telepath::buttons(
        new StartInlineButton, new RefreshInlineButton, new NoOptionInlineButton, new YesRestartInlineButton, new YesShutdownInlineButton
    )->middleware(EnableLiveReloadMiddleware::class);

});
