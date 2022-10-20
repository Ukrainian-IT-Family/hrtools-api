<?php

namespace App\Actions\PollQuestion;

use App\Actions\PollAnswer\PollAnswerEditAction;
use App\Repository\PollQuestionRepository;

final class PollQuestionEditAction
{
    public function __construct(
        private PollQuestionRepository $repository,
        private PollQuestionDeleteOldAction $questionDeleteOldAction,
        private PollAnswerEditAction $answerEditAction
    ) {
    }

    public function execute(int $pollId, array $pollQuestions): void
    {
        $this->questionDeleteOldAction->execute($pollId, $pollQuestions);

        foreach ($pollQuestions as $pollQuestion) {
            $question = $this->repository->updateOrCreate(
                [
                    'id' => array_key_exists('id', $pollQuestion) ? $pollQuestion['id'] : 0,
                    'poll_id' => $pollId,
                ],
                [
                    'name' => $pollQuestion['name'],
                    'required' => $pollQuestion['required'],
                    'type' => $pollQuestion['type'],
                    'poll_id' => $pollId,
                ]
            );

            $this->answerEditAction->execute($question->getId(), $pollQuestion['answers']);
        }
    }
}
