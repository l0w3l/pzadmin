<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Data\Auth\LoginData;
use App\Data\Auth\RegistrationData;
use App\Exceptions\Auth\InviteLimitException;
use App\Exceptions\Auth\InviteNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\LoginRequest;
use App\Http\Requests\V1\Auth\RegistrationRequest;
use App\Services\Auth\Invite\InviteServiceInterface;
use App\Services\Auth\Token\TokenServiceInterface;
use App\Services\Auth\User\UserServiceInterface;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpFoundation\Response;

final readonly class AuthController extends Controller
{
    public function __construct(
        private UserServiceInterface $userService,
        private TokenServiceInterface $tokenService,
        private InviteServiceInterface $inviteService,
    ) {}

    /**
     * @throws AuthenticationException
     */
    public function index(): Response
    {
        $userData = $this->userService->authenticated();

        return response()->json($userData);
    }

    public function ping(): Response
    {
        return response()->noContent();
    }

    public function regenerate(): Response
    {
        return response()->json($this->tokenService->regenerate(), Response::HTTP_ACCEPTED);
    }

    /**
     * @throws AuthenticationException
     */
    public function login(LoginRequest $loginRequest): Response
    {
        $loginData = LoginData::from($loginRequest);

        $tokenData = $this->tokenService->authenticate($loginData);

        return response()->json($tokenData, Response::HTTP_ACCEPTED);
    }

    /**
     * @throws AuthenticationException
     */
    public function logout(): Response
    {
        $this->tokenService->logout();

        return response()->noContent();
    }

    /**
     * @throws AuthenticationException
     * @throws InviteNotFoundException
     * @throws InviteLimitException
     */
    public function registration(RegistrationRequest $loginRequest): Response
    {
        $registrationData = RegistrationData::from($loginRequest);
        $inviteData = $this->inviteService->verifyInviteHash($registrationData->hash);

        $this->userService->register($registrationData);
        $this->inviteService->decLimit($inviteData);

        $tokenData = $this->tokenService->authenticate(LoginData::from(
            $registrationData
        ));

        return response()->json($tokenData, Response::HTTP_ACCEPTED);
    }
}
