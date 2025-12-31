<?php

declare(strict_types=1);

namespace App\Repositories\Game\Log;

use App\Data\Game\Log\LogData;
use App\Data\Game\Log\LogItemData;
use Lowel\LaravelServiceMaker\Repositories\AbstractRepository;
use SplFileObject;

class LogRepository extends AbstractRepository implements LogRepositoryInterface
{
    public function parse(LogInstanceEnum $logInstanceEnum, int $limit = 20, int $offset = 0): LogData
    {
        return new LogData(
            md5_file($logInstanceEnum->path()),
            $this->readFileLines($logInstanceEnum->path(), $limit, $offset),
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
}
