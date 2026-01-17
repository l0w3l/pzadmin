<?php

declare(strict_types=1);

namespace App\Repositories\Zomboid\Log;

use App\Data\Zomboid\Log\LogData;
use App\Data\Zomboid\Log\LogItemData;
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
        $logItems = $this->readFileLines($filePath, $limit, $offset);

        return new LogData(
            $this->getMD5Of($filePath),
            $logItems,
        );
    }

    public function parseReverse(string $filePath, int $limit = 20, int $offset = 0): LogData
    {
        $logItems = $this->readFileLinesFromEnd($filePath, $limit, $offset);

        return new LogData(
            $this->getMD5Of($filePath),
            $logItems,
        );
    }

    public function parseCursor(string $filePath, int $leftSide, int $rightSide = PHP_INT_MAX): LogData
    {
        $logItems = $this->readFileLinesCursor($filePath, $leftSide, $rightSide);

        return new LogData(
            $this->getMD5Of($filePath),
            $logItems,
        );
    }

    /**
     * @return LogItemData[]
     */
    public function readFileLines(string $filepath, int $limit, int $offset = 0): array
    {
        $result = [];

        $file = new SplFileObject($filepath, 'r');
        $file->setFlags(
            SplFileObject::DROP_NEW_LINE |
            SplFileObject::SKIP_EMPTY,
        );

        $file->seek($offset);

        $count = 0;
        while (! $file->eof() && $count < $limit) {
            $line = $file->current();
            if ($line !== false) {
                $result[] = new LogItemData($count + $offset, $line);
                $count++;
            }
            $file->next();
        }

        return $result;
    }

    /**
     * @return LogItemData[]
     */
    public function readFileLinesFromEnd(string $path, int $limit = 50, int $offset = 0): array
    {
        $file = new SplFileObject($path, 'r');
        $file->setFlags(
            SplFileObject::DROP_NEW_LINE |
            SplFileObject::SKIP_EMPTY,
        );

        $file->seek(PHP_INT_MAX);

        $lastLine = $file->key();
        $start = max(0, $lastLine - $offset - $limit);
        $end = max(-1, $lastLine - $offset);

        $lines = [];

        for ($i = $start; $i <= $end; $i++) {
            $file->seek($i);
            $line = $file->current();

            if ($line !== false) {
                $lines[] = new LogItemData($i, $line);
            }
        }

        return $lines;
    }

    /**
     * @return LogItemData[]
     */
    private function readFileLinesCursor(string $filePath, int $leftSide, int $rightSide = PHP_INT_MAX): array
    {
        $file = new SplFileObject($filePath, 'r');
        $file->setFlags(
            SplFileObject::DROP_NEW_LINE |
            SplFileObject::SKIP_EMPTY,
        );

        $file->seek(PHP_INT_MAX);

        $lastLine = $file->key();
        $start = max(-1, $leftSide + 1);
        $end = $rightSide > $lastLine ? $lastLine : max(0, $rightSide);

        $lines = [];

        for ($i = $start; $i <= $end; $i++) {
            $file->seek($i);
            $line = $file->current();

            if ($line !== false) {
                $lines[] = new LogItemData($i, $line);
            }
        }

        return $lines;
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
            })->filter(function (SplFileInfo $file) {
                preg_match('/(\d{4}-\d{2}-\d{2}_\d{2}-\d{2})/', $file->getPathname(), $ma);
                $ta = DateTimeImmutable::createFromFormat('Y-m-d_H-i', $ma[1]);

                return now()->subWeek() < $ta;
            })->name($relativeFileName);

        foreach ($finder as $file) {
            yield $file;
        }
    }

    public function getMD5Of(string $filePath): string
    {
        return md5_file($filePath);
    }
}
