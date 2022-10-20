<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Repository\UserRepository;

final class FindUserCollectionByFullNameAction
{
    public function __construct(
        private UserRepository $userRepository,
    ) {
    }

    public function execute(FindUserCollectionByFullNameRequest $request): FindUserCollectionByFullNameResponse
    {
        $users = $this->userRepository->searchUsersByFullName($request->getFullName());

        return new FindUserCollectionByFullNameResponse($users);
    }
}
