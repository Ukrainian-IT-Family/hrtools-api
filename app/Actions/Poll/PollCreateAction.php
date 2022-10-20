<?php

declare(strict_types=1);

namespace App\Actions\Poll;

use App\Actions\PollQuestion\PollQuestionCreateAction;
use App\Models\Poll;
use App\Repository\PollRepository;

final class PollCreateAction
{
    public function __construct(private PollRepository $pollRepository,
    private PollQuestionCreateAction $questionCreateAction
    ) {
    }

    public function execute(PollCreateRequest $request): PollResponse
    {
        $poll = $this->pollRepository->create([
            'title' => $request->getTitle(),
            'status' => Poll::NEW_STATUS_ID,
            'anonymous' => $request->isAnonymous(),
        ]);
        $poll = $this->pollRepository->save($poll);

        $this->questionCreateAction->execute($poll->getId(), $request->getQuestions());

        return new PollResponse($poll);
    }
}
