<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Repository\UserRepository;

final class GetWorkerCollectionAction
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function execute(): GetUserCollectionResponse
    {
        $users = $this->userRepository->getWorkersList();

        return new GetUserCollectionResponse($users);
    }
}
