<?php

namespace App\Actions\PollQuestion;

use App\Repository\PollQuestionRepository;

final class PollQuestionDeleteOldAction
{
    public function __construct(
        private PollQuestionRepository $repository
    ) {
    }

    public function execute(int $pollId, array $pollQuestions): void
    {
        $questionsIds = collect($pollQuestions)->pluck('id', 'id')->toArray();
        $questions = $this->repository->getQuestionsByPollId($pollId);
        foreach ($questions as $question) {
            if (! in_array($question['id'], $questionsIds)) {
                $this->repository->delete($question['id']);
            }
        }
    }
}
