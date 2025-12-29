<?php

namespace App\Exceptions\Repositories\Game\Log;

use App\Data\Game\Log\LogData;
use App\Exceptions\CheckedException;

class LogDataNotFoundException extends CheckedException
{
    protected string $messageFormat = 'Log was not founded: %s';

    public function __construct(LogData $logData)
    {
        parent::__construct('Log was not founded: '.$logData->toJson(JSON_PRETTY_PRINT));
    }
}
