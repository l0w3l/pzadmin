<?php

namespace App\Http\Controllers\Api\V1\Zomboid;

use App\Http\Controllers\Controller;
use App\Services\Game\Log\LogServiceInterface;
use Symfony\Component\HttpFoundation\Response;

final readonly class LogsController extends Controller
{
    public function __construct(
        public LogServiceInterface $logService
    ) {}

    public function console(): Response
    {
        return $this->json($this->logService->getServerConsoleLogs());
    }
}
