<?php

declare(strict_types=1);

namespace App\Actions\Poll;

use App\Actions\PollQuestion\PollQuestionEditAction;
use App\Repository\PollRepository;

final class PollEditAction
{
    public function __construct(private PollRepository $pollRepository,
    private PollQuestionEditAction $questionEditAction
    ) {
    }

    public function execute(PollEditRequest $request): PollResponse
    {
        $poll = $this->pollRepository->getById($request->getId());
        $poll->title = $request->getTitle();
        $poll->anonymous = $request->isAnonymous();

        $this->questionEditAction->execute($request->getId(), $request->getQuestions());

        $poll = $this->pollRepository->save($poll);

        $poll->refresh();

        return new PollResponse($poll);
    }
}
