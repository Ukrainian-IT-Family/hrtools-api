<?php

declare(strict_types=1);

namespace App\Actions\Auth;

final class AuthenticationResponse
{
    public function __construct(
        private object $user,
        private string $accessToken,
        private string $tokenType,
        private int $expiresIn
    ) {
    }

    public function getUser(): object
    {
        return $this->user;
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function getTokenType(): string
    {
        return $this->tokenType;
    }

    public function getExpiresIn(): int
    {
        return $this->expiresIn;
    }
}
