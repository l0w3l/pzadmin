<?php

declare(strict_types=1);

namespace App\Services\Auth\Token;

use App\Data\Auth\LoginData;
use App\Data\Auth\TokenData;
use Illuminate\Auth\AuthenticationException;
use Lowel\LaravelServiceMaker\Services\ServiceInterface;

interface TokenServiceInterface extends ServiceInterface
{
    /**
     * @throws AuthenticationException
     */
    public function authenticate(LoginData $loginData): TokenData;

    public function regenerate(): TokenData;

    /**
     * @throws AuthenticationException
     */
    public function logout(): void;
}
