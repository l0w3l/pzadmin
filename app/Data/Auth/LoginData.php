<?php

namespace App\Data\Auth;

use Illuminate\Support\Str;
use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Data;

class LoginData extends Data
{
    #[Computed]
    public readonly string $username;

    public function __construct(
        string $username,
        #[\SensitiveParameter]
        public readonly string $password,
        public readonly bool $remember_me = false,
    ) {
        $this->username = Str::lower($username);
    }
}
