<?php

declare(strict_types=1);

namespace App\Actions\Poll;

use App\Repository\PollRepository;
use Illuminate\Support\Facades\Auth;

final class GetLatestPollsForUserAction
{
    public function __construct(private PollRepository $pollRepository
    ) {
    }

    public function execute(): PollCollectionResponse
    {
        $user = Auth::user();

        $polls = $this->pollRepository->getLatestPollsForUser($user);

        return new PollCollectionResponse($polls);
    }
}
