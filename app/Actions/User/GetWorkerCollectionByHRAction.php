<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Repository\UserRepository;
use Illuminate\Support\Facades\Auth;

final class GetWorkerCollectionByHRAction
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function execute(): GetUserCollectionResponse
    {
        $userId = Auth::id();
        $users = $this->userRepository->getUsersByHR($userId);

        return new GetUserCollectionResponse($users);
    }
}
