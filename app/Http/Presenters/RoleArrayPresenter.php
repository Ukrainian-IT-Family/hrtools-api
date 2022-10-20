<?php

declare(strict_types=1);

namespace App\Http\Presenters;

use App\Actions\Role\RoleCollectionResponse;
use App\Actions\Role\RoleResponse;
use App\Models\Role;

final class RoleArrayPresenter
{
    public function present(RoleResponse $role): array
    {
        return [
            'id' => $role->getRoleId(),
            'name' => $role->getRoleName(),
        ];
    }

    public function getCollection(RoleCollectionResponse $roles): array
    {
        return $roles->getRole()->map(
            function (Role $role) {
                return $this->present(new RoleResponse($role));
            }
        )->all();
    }
}
