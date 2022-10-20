<?php

declare(strict_types=1);

namespace App\Actions\Auth;

final class ForgotPasswordRequest
{
    public function __construct(
        private string $email
    ) {
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
