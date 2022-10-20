<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Models\User;

final class GetAuthenticatedUserResponse
{
    public function __construct(private User $user)
    {
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
