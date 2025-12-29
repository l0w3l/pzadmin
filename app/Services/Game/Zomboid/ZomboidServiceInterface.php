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

    /**
     * @param  null|callable(ServerData $serverData): void  $onChange
     */
    public function updateStatus(?callable $onChange = null): void;

    public function doStart(): bool;

    public function doDown(): bool;

    public function doRestart(): bool;
}
