<?php

namespace App\Http\Controllers\Api\V1\Users;

use App\Http\Controllers\Controller;
use App\Services\Auth\User\UserServiceInterface;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpFoundation\Response;

final readonly class UsersController extends Controller
{
    public function __construct(
        private UserServiceInterface $userService
    ) {}

    /**
     * @throws AuthenticationException
     */
    public function auth(): Response
    {
        $user = $this->userService->authenticated();

        return response()->json($user, Response::HTTP_ACCEPTED);
    }
}
