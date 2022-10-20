<?php

declare(strict_types=1);

namespace App\Actions\Role;

final class RoleResponse
{
    public function __construct(
        private object $role,
    ) {
    }

    public function getRoleName(): string
    {
        return $this->role->name;
    }

    public function getRoleId(): int
    {
        return $this->role->id;
    }
}
