<?php

declare(strict_types=1);

namespace App\Services\Auth\User;

use App\Data\Auth\RegistrationData;
use App\Data\Auth\UserData;
use App\Repositories\Auth\User\UserRepositoryInterface;
use App\Services\Abstract\AbstractService;

class UserService extends AbstractService implements UserServiceInterface
{
    public function __construct(
        public readonly UserRepositoryInterface $userRepository
    ) {}

    public function authenticated(): UserData
    {
        return $this->userRepository->authenticated();
    }

    public function register(RegistrationData $registrationData): UserData
    {
        return $this->userRepository->create($registrationData);
    }

    public function findUserByUsername(string $username): UserData
    {
        return $this->userRepository->findByUsername($username);
    }

    public function findUserByEmail(string $email): UserData
    {
        return $this->userRepository->findByEmail($email);
    }
}
