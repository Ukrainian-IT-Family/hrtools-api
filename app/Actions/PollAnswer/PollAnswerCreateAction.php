<?php

namespace App\Actions\PollAnswer;

use App\Repository\PollAnswerRepository;

final class PollAnswerCreateAction
{
    public function __construct(private PollAnswerRepository $repository)
    {
    }

    public function execute(int $questionId, array $pollAnswers): void
    {
        foreach ($pollAnswers as $answer) {
            $this->repository->create([
                'value' => $answer['value'],
                'poll_question_id' => $questionId,
            ]);
        }
    }
}
