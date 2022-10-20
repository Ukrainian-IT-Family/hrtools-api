<?php

declare(strict_types=1);

namespace App\Actions\Auth;

final class RegisterRequest
{
    public function __construct(
        private string $firstName,
        private string $lastName,
        private string $email,
        private string $phone,
        private string $password
    ) {
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
