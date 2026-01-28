<?php

declare(strict_types=1);

namespace App\Telegram\Keyboards\Inline\Zomboid;

use App\Telegram\Keyboards\Inline\Zomboid\Buttons\Confirmation\FakeYesRestartOptionInlineButton;
use App\Telegram\Keyboards\Inline\Zomboid\Buttons\Confirmation\FakeYesShutdownOptionInlineButton;
use App\Telegram\Keyboards\Inline\Zomboid\Buttons\Confirmation\NoOptionInlineButton;
use App\Telegram\Keyboards\Inline\Zomboid\Buttons\Confirmation\YesRestartInlineButton;
use App\Telegram\Keyboards\Inline\Zomboid\Buttons\Confirmation\YesShutdownInlineButton;
use App\Telegram\Keyboards\Inline\Zomboid\Buttons\RefreshInlineButton;
use App\Telegram\Keyboards\Inline\Zomboid\Buttons\RestartInlineButton;
use App\Telegram\Keyboards\Inline\Zomboid\Buttons\StartInlineButton;
use App\Telegram\Keyboards\Inline\Zomboid\Buttons\StopInlineButton;
use Lowel\Telepath\Core\Router\Keyboard\InlineKeyboardBuilder;
use Lowel\Telepath\Core\Router\Keyboard\KeyboardBuilderInterface;
use Lowel\Telepath\Core\Router\Keyboard\KeyboardFactoryInterface;

class ZomboidInlineKeyboardFactory implements KeyboardFactoryInterface
{
    public static function isPending(): KeyboardBuilderInterface
    {
        $builder = new InlineKeyboardBuilder;

        return $builder->row(new StopInlineButton, new RefreshInlineButton);
    }

    public static function isActive(): KeyboardBuilderInterface
    {
        $builder = new InlineKeyboardBuilder;

        return $builder->row(new StopInlineButton, new RefreshInlineButton, new RestartInlineButton);
    }

    public static function backup(): KeyboardBuilderInterface
    {
        $builder = new InlineKeyboardBuilder;

        return $builder->row(new RefreshInlineButton);
    }

    public static function isDead(): KeyboardBuilderInterface
    {
        $builder = new InlineKeyboardBuilder;

        return $builder->row(new StartInlineButton, new RefreshInlineButton, new RestartInlineButton);
    }

    public static function fakeYesRestartConfirmation(): KeyboardBuilderInterface
    {
        $builder = new InlineKeyboardBuilder;

        return $builder->row(new FakeYesRestartOptionInlineButton, new NoOptionInlineButton);
    }

    public static function restartConfirmation(): KeyboardBuilderInterface
    {
        $builder = new InlineKeyboardBuilder;

        return $builder->row(new NoOptionInlineButton, new YesRestartInlineButton);
    }

    public static function fakeYesShutdownConfirmation(): KeyboardBuilderInterface
    {
        $builder = new InlineKeyboardBuilder;

        return $builder->row(new FakeYesShutdownOptionInlineButton, new NoOptionInlineButton);
    }

    public static function shutdownConfirmation(): KeyboardBuilderInterface
    {
        $builder = new InlineKeyboardBuilder;

        return $builder->row(new NoOptionInlineButton, new YesShutdownInlineButton);
    }

    public function make(): KeyboardBuilderInterface
    {
        $builder = new InlineKeyboardBuilder;

        return $builder->row(new StopInlineButton, new RefreshInlineButton, new RestartInlineButton, new StartInlineButton, new NoOptionInlineButton, new YesShutdownInlineButton, new FakeYesShutdownOptionInlineButton, new FakeYesRestartOptionInlineButton, new YesRestartInlineButton);
    }
}
