<?php

declare(strict_types=1);

namespace App\Services\Auth\User;

use App\Data\Auth\RegistrationData;
use App\Data\Auth\UserData;
use App\Exceptions\Auth\UserNotFoundException;
use Illuminate\Auth\AuthenticationException;
use Lowel\LaravelServiceMaker\Services\ServiceInterface;

interface UserServiceInterface extends ServiceInterface
{
    /**
     * @throws AuthenticationException
     */
    public function authenticated(): UserData;

    public function register(RegistrationData $registrationData): UserData;

    /**
     * @throws UserNotFoundException
     */
    public function findUserByUsername(string $username): UserData;

    /**
     * @throws UserNotFoundException
     */
    public function findUserByEmail(string $email): UserData;
}
