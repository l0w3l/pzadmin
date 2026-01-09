<?php

namespace App\Http\Controllers\Api\V1\Zomboid;

use App\Http\Controllers\Controller;
use App\Services\Zomboid\Log\LogServiceInterface;
use Symfony\Component\HttpFoundation\Response;

final readonly class LogsController extends Controller
{
    public function __construct(
        public LogServiceInterface $logService
    ) {}

    public function console(): Response
    {
        return response()->json($this->logService->readServerConsole(1000));
    }
}
