<?php

declare(strict_types=1);

namespace App\Repositories\Game\Log;

use App\Data\Game\LogData;
use App\Data\Game\LogInstanceData;
use App\Repositories\Abstract\AbstractRepository;
use App\Repositories\Game\Log\Parser\ParserFactoryInterface;
use Illuminate\Support\Collection;
use RuntimeException;

class FilesystemLogRepository extends AbstractRepository implements LogRepositoryInterface
{
    public readonly ParserFactoryInterface $parserFactory;

    public function __construct()
    {
        $this->parserFactory = app(ParserFactoryInterface::class);
    }

    public function all(): Collection
    {
        return new Collection;
    }

    public function allByInstance(LogInstanceData $logInstanceData): Collection
    {
        $parser = $this->parserFactory->getTxtParser($logInstanceData->name->path());

        $parsedData = collect($parser->parse());

        return LogData::collect($parsedData);
    }

    public function save(LogInstanceData $logInstanceData, Collection $logDataCollection): Collection
    {
        throw new RuntimeException('Non supported action for filesystem log repository');
    }

    public function append(LogInstanceData $logsInstance, Collection $logsInstanceData): Collection
    {
        throw new RuntimeException('Non supported action for filesystem log repository');
    }

    public function reset(LogInstanceData $logInstanceData): void
    {
        throw new RuntimeException('Non supported action for filesystem log repository');
    }
}
