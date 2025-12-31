<?php

declare(strict_types=1);

use App\Telegram\Handlers\StartHandler;
use App\Telegram\Keyboards\Inline\Zomboid\ZomboidInlineKeyboardFactory;
use App\Telegram\Middlewares\OnlyForChatMiddleware;
use Lowel\Telepath\Facades\Extrasense;
use Lowel\Telepath\Facades\Telepath;

//Telepath::middleware(OnlyForChatMiddleware::class)->group(function () {
    Telepath::onMessage(StartHandler::class, "\/start(".Extrasense::profile()->username.')?');

    Telepath::keyboard(ZomboidInlineKeyboardFactory::class);
//});
