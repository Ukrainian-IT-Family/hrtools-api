<?php

declare(strict_types=1);

namespace App\Actions\Auth;

final class ResetPasswordRequest
{
    public function __construct(
        private string $token,
        private string $email,
        private string $password,
        private string $password_confirmation,
    ) {
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getPasswordConfirmation(): string
    {
        return $this->password_confirmation;
    }
}
