<?php

declare(strict_types=1);

namespace App\Actions\Poll;

use App\Repository\PollRepository;
use App\Repository\PollResultRepository;

final class PollViewAction
{
    public function __construct(
        private PollRepository $pollRepository,
        private PollResultRepository $resultRepository
    ) {
    }

    public function execute(PollViewRequest $request): PollViewResponse
    {
        $userId = \Auth::id();
        $poll = $this->pollRepository->getById($request->getPollId());
        $pollResult = $this->resultRepository->getResultByPollIdAndByUser($poll->getId(), $userId);

        return new PollViewResponse($poll, $pollResult);
    }
}
