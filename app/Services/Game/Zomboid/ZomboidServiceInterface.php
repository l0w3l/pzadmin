<?php

declare(strict_types=1);

namespace App\Services\Game\Zomboid;

use App\Data\Game\ServerData;

/**
 * Service for zomboid server management
 */
interface ZomboidServiceInterface
{
    public function getServer(): ServerData;

    public function doStart(): bool;

    public function doDown(): bool;

    public function doRestart(): bool;
}
