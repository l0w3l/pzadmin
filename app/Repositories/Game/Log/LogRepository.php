<?php

declare(strict_types=1);

namespace App\Repositories\Game\Log;

use App\Data\Game\Log\LogData;
use App\Data\Game\Log\LogItemData;
use Generator;
use Lowel\LaravelServiceMaker\Repositories\AbstractRepository;
use SplFileObject;
use Symfony\Component\Finder\Finder;

class LogRepository extends AbstractRepository implements LogRepositoryInterface
{
    public function parse(string $filePath, int $limit = 20, int $offset = 0): LogData
    {
        return new LogData(
            md5_file($filePath),
            $this->readFileLines($filePath, $limit, $offset),
        );
    }

    /**
     * @return LogItemData[]
     */
    public function readFileLines(string $filepath, int $limit, int $offset = 0): array
    {
        $result = [];

        $fh = new SplFileObject($filepath, 'r');
        $fh->setFlags(
            SplFileObject::DROP_NEW_LINE |
            SplFileObject::SKIP_EMPTY
        );

        $fh->seek($offset);

        $count = 0;
        while (! $fh->eof() && $count < $limit) {
            $line = $fh->current();
            if ($line !== false) {
                $result[] = new LogItemData($count + $offset, $line);
                $count++;
            }
            $fh->next();
        }

        return $result;
    }

    public function parseNnAllSubDirectories(string $directoryPath, string $relativeFileName): Generator
    {
        $finder = Finder::create()
            ->files()
            ->in($directoryPath)
            ->name($relativeFileName);

        foreach ($finder as $file) {
            yield $file;
        }
    }
}
