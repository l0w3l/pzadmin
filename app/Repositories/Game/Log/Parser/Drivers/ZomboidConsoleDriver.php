<?php

declare(strict_types=1);

namespace App\Repositories\Game\Log\Parser\Drivers;

use App\Repositories\Game\Log\Parser\Drivers\Formatters\ServiceFormattersEnum;

class ZomboidConsoleDriver implements ParserDriverInterface
{
    public function template(): string
    {
        return '@type@:@scope@,@seed1@>@seed2@>@message';
    }

    public function formatters(): array
    {
        return [
            ServiceFormattersEnum::EACH->value => fn ($value) => trim($value),
            ServiceFormattersEnum::IGNORE->value => fn () => ['seed1'],
        ];
    }
}
