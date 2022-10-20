<?php

declare(strict_types=1);

namespace App\Actions\Poll;

use App\Actions\PaginatedResponse;
use App\Repository\PollRepository;
use App\Repository\UserRepository;
use Illuminate\Support\Facades\Auth;

final class GetPollsForAdminAction
{
    public function __construct(
        private PollRepository $pollRepository,
        private UserRepository $userRepository
    ) {
    }

    public function execute(GetPollCollectionRequest $request): PaginatedResponse
    {
        $userId = Auth::id();
        $admins = $this->userRepository->getAdmins();
        $adminsIds = collect($admins)->pluck('id', 'id')->toArray();
        unset($admins[$userId]);

        $paginator = $this->pollRepository->getPaginatedForAdmin(
            $adminsIds,
            $request->getPage() ?: PollRepository::DEFAULT_PAGE,
            PollRepository::DEFAULT_PER_PAGE,
        );

        return new PaginatedResponse($paginator);
    }
}
