<?php

declare(strict_types=1);

namespace App\Services\Zomboid\Rcon;

use Lowel\LaravelServiceMaker\Services\ServiceInterface;

interface RconServiceInterface extends ServiceInterface
{
    /**
     * Exit server
     */
    public function quite(): void;

    /**
     * Save server state
     */
    public function save(): void;
}
