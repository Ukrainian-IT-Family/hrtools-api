<?php

declare(strict_types=1);

namespace App\Actions\Role;

use Illuminate\Database\Eloquent\Collection;

final class RoleCollectionResponse
{
    public function __construct(
        private $role,
    ) {
    }

    public function getRole(): Collection
    {
        return $this->role;
    }
}
