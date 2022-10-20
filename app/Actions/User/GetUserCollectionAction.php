<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Repository\UserRepository;

final class GetUserCollectionAction
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function execute(): GetUserCollectionResponse
    {
        $users = $this->userRepository->getAll();

        return new GetUserCollectionResponse($users);
    }
}
