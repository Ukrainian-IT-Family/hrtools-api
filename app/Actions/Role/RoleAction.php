<?php

declare(strict_types=1);

namespace App\Actions\Role;

use App\Repository\RoleRepository;

final class RoleAction
{
    public function __construct(private RoleRepository $roleRepository)
    {
    }

    public function execute(RoleRequest $request): RoleResponse
    {
        $roleId = $request->getRoleId();
        $role = $this->roleRepository->getById($roleId);

        return new RoleResponse($role);
    }
}
