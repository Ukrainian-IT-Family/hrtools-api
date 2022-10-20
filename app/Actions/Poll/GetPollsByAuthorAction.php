<?php

declare(strict_types=1);

namespace App\Actions\Poll;

use App\Actions\PaginatedResponse;
use App\Repository\PollRepository;
use Illuminate\Support\Facades\Auth;

final class GetPollsByAuthorAction
{
    public function __construct(private PollRepository $pollRepository
    ) {
    }

    public function execute(GetPollCollectionRequest $request): PaginatedResponse
    {
        $userId = Auth::id();

        $paginator = $this->pollRepository->getPaginatedCreatedByUser(
            $userId,
            $request->getPage() ?: PollRepository::DEFAULT_PAGE,
            PollRepository::DEFAULT_PER_PAGE,
        );

        return new PaginatedResponse($paginator);
    }
}
