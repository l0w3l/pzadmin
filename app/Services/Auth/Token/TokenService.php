<?php

declare(strict_types=1);

namespace App\Services\Auth\Token;

use App\Data\Auth\LoginData;
use App\Data\Auth\TokenData;
use App\Exceptions\Auth\IncorrectPasswordException;
use App\Exceptions\Auth\TokenNotFoundException;
use App\Exceptions\Auth\UserNotFoundException;
use App\Repositories\Auth\Token\TokenRepositoryInterface;
use App\Repositories\Auth\User\UserRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Hash;
use Lowel\LaravelServiceMaker\Services\AbstractService;

class TokenService extends AbstractService implements TokenServiceInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly TokenRepositoryInterface $tokenRepository
    ) {}

    public function authenticate(LoginData $loginData): TokenData
    {
        try {
            $userData = $this->userRepository->findByUsername($loginData->username);

            if (! Hash::check($loginData->password, $userData->password)) {
                throw new IncorrectPasswordException($loginData->username);
            }
        } catch (UserNotFoundException|IncorrectPasswordException) {
            throw new AuthenticationException;
        }

        if ($loginData->remember_me) {
            $longExpiresTime = Carbon::now()->addMonth();
        }

        return $this->tokenRepository->createFor($userData, expiresAt: $longExpiresTime ?? null);
    }

    /**
     * @throws TokenNotFoundException
     * @throws AuthenticationException
     */
    public function regenerate(): TokenData
    {
        $userData = $this->userRepository->authenticated();
        $tokenId = $this->getTokenIdFromRequest();

        return $this->tokenRepository->update($userData, $tokenId);
    }

    public function logout(): void
    {
        $tokenId = $this->getTokenIdFromRequest();

        $this->tokenRepository->delete($tokenId);
    }

    private function getTokenIdFromRequest(): int
    {
        $request = request();
        $token = $request->header('Authorization');

        return (int) explode('|', str_replace('Bearer ', '', $token))[0];
    }
}
