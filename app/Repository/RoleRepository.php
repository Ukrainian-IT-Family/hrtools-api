<?php

declare(strict_types=1);

namespace App\Repository;

use App\Models\Role;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class RoleRepository
{
    public function create(array $fields): Role
    {
        return Role::create($fields);
    }

    /**
     * @param  int  $id
     * @return Role
     *
     * @throws ModelNotFoundException
     */
    public function getById(int $id): Role
    {
        return Role::findOrFail($id);
    }

    public function save(Role $role): Role
    {
        $role->save();

        return $role;
    }

    public function getAll(): Collection
    {
        return Role::query()->orderBy('name', 'asc')->get();
    }
}
