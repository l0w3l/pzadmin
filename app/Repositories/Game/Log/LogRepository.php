<?php

declare(strict_types=1);

namespace App\Repositories\Game\Log;

use App\Data\Game\Log\LogData;
use App\Data\Game\Log\LogItemData;
use DateTimeImmutable;
use Generator;
use Lowel\LaravelServiceMaker\Repositories\AbstractRepository;
use SplFileObject;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

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

    public function parseAllSubDirectories(string $directoryPath, string $relativeFileName): Generator
    {
        $finder = Finder::create()
            ->files()
            ->in($directoryPath)
            ->sort(function (SplFileInfo $a, SplFileInfo $b) {
                preg_match('/(\d{4}-\d{2}-\d{2}_\d{2}-\d{2})/', $a->getPathname(), $ma);
                preg_match('/(\d{4}-\d{2}-\d{2}_\d{2}-\d{2})/', $b->getPathname(), $mb);

                $ta = DateTimeImmutable::createFromFormat('Y-m-d_H-i', $ma[1])->getTimestamp();
                $tb = DateTimeImmutable::createFromFormat('Y-m-d_H-i', $mb[1])->getTimestamp();

                return $ta <=> $tb;
            })
            ->name($relativeFileName);

        foreach ($finder as $file) {
            yield $file;
        }
    }
}
