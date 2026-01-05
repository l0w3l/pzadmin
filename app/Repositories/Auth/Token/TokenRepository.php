<?php

declare(strict_types=1);

namespace App\Repositories\Auth\Token;

use App\Data\Auth\TokenData;
use App\Data\Auth\UserData;
use App\Exceptions\Auth\TokenNotFoundException;
use App\Exceptions\Auth\UserNotFoundException;
use App\Models\Auth\PersonalAccessToken;
use App\Models\Auth\User;
use Carbon\Carbon;
use DateTimeInterface;
use Lowel\LaravelServiceMaker\Repositories\AbstractRepository;

class TokenRepository extends AbstractRepository implements TokenRepositoryInterface
{
    public function createFor(UserData $userData, array $abilities = ['*'], ?DateTimeInterface $expiresAt = null): TokenData
    {
        /** @var User $user */
        $user = User::whereId($userData->id)->first();

        $newToken = $user->createToken($user->username, $abilities, $expiresAt ?? $this->getExpiredAtTimeDefault());
        /** @var PersonalAccessToken $personalAccessToken */
        $personalAccessToken = $newToken->accessToken;

        return new TokenData($newToken->plainTextToken, $personalAccessToken->expires_at);
    }

    /**
     * @throws UserNotFoundException
     * @throws TokenNotFoundException
     */
    public function update(UserData $userData, int $tokenId): TokenData
    {
        /** @var User $user */
        $user = User::whereId($userData->id)->first() ?? (throw new UserNotFoundException($userData->username));

        /** @var PersonalAccessToken $oldToken */
        $oldToken = $user->tokens()->where('id', $tokenId)->first() ?? throw new TokenNotFoundException($userData);

        $oldToken->delete();
        $newToken = $user->createToken($userData->username, $oldToken->abilities ?? ['*'], $this->getExpiredAtTimeDefault());
        /** @var PersonalAccessToken $personalAccessToken */
        $personalAccessToken = $newToken->accessToken;

        return new TokenData($newToken->plainTextToken, $personalAccessToken->expires_at);
    }

    public function delete(int $tokenId): void
    {
        PersonalAccessToken::whereId($tokenId)->delete();
    }

    public function getExpiredAtTimeDefault(): DateTimeInterface
    {
        return Carbon::now()->addMinutes(config('sanctum.expiration'));
    }
}
