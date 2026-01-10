<?php

namespace App\Http\Controllers\Api\V1\Zomboid;

use App\Http\Controllers\Controller;
use App\Services\Zomboid\Log\LogServiceInterface;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final readonly class LogsController extends Controller
{
    public function __construct(
        public LogServiceInterface $logService
    ) {}

    public function console(Request $request): Response
    {
        $limit = $request->get('limit', 10000);
        $offset = $request->get('offset', 0);

        if ($limit > 10000) {
            return response()->json(['message' => 'Limit should be <=1000'], Response::HTTP_BAD_REQUEST);
        }

        $logData = $this->logService->readServerConsole($limit, $offset);

        return response()->json($logData);
    }

    public function console_cursor(int $leftRange, int $rightRange): Response
    {
        if ($leftRange > $rightRange) {
            return response()->json(['message' => 'Left range should be lower than right range'], Response::HTTP_BAD_REQUEST);
        }

        $logData = $this->logService->readServerConsoleCursor($leftRange, $rightRange);

        return response()->json($logData);
    }
}
