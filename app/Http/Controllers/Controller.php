<?php

namespace App\Http\Controllers;

use Illuminate\Pagination\AbstractPaginator;
use JsonSerializable;
use Symfony\Component\HttpFoundation\Response;

abstract readonly class Controller
{
    /**
     * @param  JsonSerializable|AbstractPaginator<int, mixed>|array<mixed>  $data
     */
    protected function json(JsonSerializable|AbstractPaginator|array $data, int $statusCode = Response::HTTP_OK): Response
    {
        $response = response();

        if ($data instanceof JsonSerializable) {
            if ($data instanceof AbstractPaginator) {
                $response = $response->json($data->jsonSerialize());
            } else {
                $response = $response->json([
                    'data' => $data->jsonSerialize(),
                ]);
            }
        } else {
            $response = $response->json([
                'data' => $data,
            ]);
        }

        return $response->setStatusCode($statusCode);
    }

    protected function notFound(): Response
    {
        return response()->json()->setStatusCode(Response::HTTP_NOT_FOUND);
    }

    protected function noContent(): Response
    {
        return response()->json()->setStatusCode(Response::HTTP_NO_CONTENT);
    }

    protected function accepted(): Response
    {
        return response()->json()->setStatusCode(Response::HTTP_ACCEPTED);
    }
}
