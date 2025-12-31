<?php

declare(strict_types=1);

use App\Telegram\Handlers\StartHandler;
use App\Telegram\Keyboards\Inline\Zomboid\ZomboidInlineKeyboardFactory;
use Lowel\Telepath\Facades\Extrasense;
use Lowel\Telepath\Facades\Telepath;

Telepath::onMessage(StartHandler::class, "\/start(".Extrasense::profile()->username.')?');

Telepath::keyboard(ZomboidInlineKeyboardFactory::class);

