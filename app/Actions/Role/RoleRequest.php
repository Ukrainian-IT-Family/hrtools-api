<?php

declare(strict_types=1);

namespace App\Actions\Role;

final class RoleRequest
{
    public function __construct(
        private int $role_id,
    ) {
    }

    public function getRoleId(): int
    {
        return $this->role_id;
    }
}
