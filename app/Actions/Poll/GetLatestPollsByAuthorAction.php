<?php

declare(strict_types=1);

namespace App\Actions\Poll;

use App\Repository\PollRepository;
use Illuminate\Support\Facades\Auth;

final class GetLatestPollsByAuthorAction
{
    public function __construct(private PollRepository $pollRepository
    ) {
    }

    public function execute(): PollCollectionResponse
    {
        $userId = Auth::id();

        $polls = $this->pollRepository->getLatestPollsCreatedByUser($userId);

        return new PollCollectionResponse($polls);
    }
}
