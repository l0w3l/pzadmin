<?php

declare(strict_types=1);

namespace App\Repositories\Auth\User;

use App\Data\Auth\RegistrationData;
use App\Data\Auth\UserData;
use App\Exceptions\Auth\UserNotFoundException;
use App\Models\Auth\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Lowel\LaravelServiceMaker\Repositories\AbstractRepository;

class UserRepository extends AbstractRepository implements UserRepositoryInterface
{
    public function authenticated(): UserData
    {
        /** @var User $user */
        $user = Auth::user() ?? throw new AuthenticationException;

        return UserData::from($user);
    }

    public function findByUsername(string $username): UserData
    {
        $username = Str::lower($username);

        $user = User::whereUsername($username)->first() ?? throw new UserNotFoundException($username);

        return UserData::from($user);
    }

    public function findByEmail(string $email): UserData
    {
        $email = Str::lower($email);

        $user = User::whereEmail($email)->first() ?? throw new UserNotFoundException($email);

        return UserData::from($user);
    }

    public function create(RegistrationData $registrationData): UserData
    {
        /** @var User $user */
        $user = User::create($registrationData->toArray());

        return UserData::from($user);
    }
}
