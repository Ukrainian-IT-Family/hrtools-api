<?php

declare(strict_types=1);

namespace App\Actions\Role;

use App\Repository\RoleRepository;

final class RoleCollectionAction
{
    public function __construct(private RoleRepository $roleRepository)
    {
    }

    public function execute(): RoleCollectionResponse
    {
        $roles = $this->roleRepository->getAll();

        return new RoleCollectionResponse($roles);
    }
}
