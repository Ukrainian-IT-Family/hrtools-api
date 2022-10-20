<?php

declare(strict_types=1);

namespace App\Actions\Poll;

use App\Repository\PollRepository;

final class PollAction
{
    public function __construct(private PollRepository $pollRepository)
    {
    }

    public function execute(PollRequest $request): PollResponse
    {
        $poll = $this->pollRepository->getById($request->getPollId());

        return new PollResponse($poll);
    }
}
