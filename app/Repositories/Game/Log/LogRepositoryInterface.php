<?php

declare(strict_types=1);

namespace App\Repositories\Game\Log;

use App\Data\Game\Log\LogData;
use Generator;
use Lowel\LaravelServiceMaker\Repositories\RepositoryInterface;
use Symfony\Component\Finder\SplFileInfo;

interface LogRepositoryInterface extends RepositoryInterface
{
    public function parse(string $filePath, int $limit = 20, int $offset = 0): LogData;

    /**
     * @return Generator<SplFileInfo>
     */
    public function parseAllSubDirectories(string $directoryPath, string $relativeFileName): Generator;
}
