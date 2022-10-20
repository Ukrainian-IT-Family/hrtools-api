<?php

namespace App\Actions\PollAnswer;

use App\Repository\PollAnswerRepository;

final class PollAnswerEditAction
{
    public function __construct(private PollAnswerRepository $repository)
    {
    }

    public function execute(int $questionId, array $pollAnswers): void
    {
        foreach ($pollAnswers as $answer) {
            $this->repository->updateOrCreate(
                [
                    'id' => array_key_exists('id', $answer) ? $answer['id'] : 0,
                    'poll_question_id' => $questionId,
                ],
                [
                    'value' => $answer['value'],
                    'poll_question_id' => $questionId,
                ]);
        }
    }
}
