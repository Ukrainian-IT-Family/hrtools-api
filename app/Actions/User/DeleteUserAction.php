<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Exceptions\UserNotFoundException;
use App\Repository\UserRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class DeleteUserAction
{
    public function __construct(private UserRepository $repository)
    {
    }

    public function execute(DeleteUserRequest $request): void
    {
        try {
            $user = $this->repository->getById($request->getId());
        } catch (ModelNotFoundException) {
            throw new UserNotFoundException();
        }

        $this->repository->delete($user);
    }
}
