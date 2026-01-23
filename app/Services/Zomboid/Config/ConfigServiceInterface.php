<?php

declare(strict_types=1);

namespace App\Services\Zomboid\Config;

use App\Data\Zomboid\Config\ConfigFieldData;
use Lowel\LaravelServiceMaker\Services\ServiceInterface;

interface ConfigServiceInterface extends ServiceInterface
{
    /**
     * @return ConfigFieldData[]
     */
    public function parseMainIniFile(): array;
}
