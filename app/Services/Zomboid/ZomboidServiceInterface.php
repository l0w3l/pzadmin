<?php

declare(strict_types=1);

namespace App\Services\Zomboid;

use App\Data\Zomboid\ServerData;
use Lowel\LaravelServiceMaker\Services\ServiceInterface;

/**
 * Service for zomboid server management
 */
interface ZomboidServiceInterface extends ServiceInterface
{
    public function getServer(): ServerData;

    public function doStart(): bool;

    public function doStop(): bool;

    public function doRestart(): bool;
}
