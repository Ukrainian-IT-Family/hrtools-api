<?php

namespace App\Actions\PollQuestion;

use App\Actions\PollAnswer\PollAnswerCreateAction;
use App\Repository\PollQuestionRepository;

final class PollQuestionCreateAction
{
    public function __construct(
        private PollQuestionRepository $repository,
        private PollAnswerCreateAction $answerCreateAction
    ) {
    }

    public function execute(int $pollId, array $pollQuestions): void
    {
        foreach ($pollQuestions as $pollQuestion) {
            $question = $this->repository->create([
                'name' => $pollQuestion['name'],
                'required' => $pollQuestion['required'],
                'type' => $pollQuestion['type'],
                'poll_id' => $pollId,
            ]);

            $this->answerCreateAction->execute($question->getId(), $pollQuestion['answers']);
        }
    }
}
