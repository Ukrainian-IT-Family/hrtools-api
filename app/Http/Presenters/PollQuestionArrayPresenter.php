<?php

declare(strict_types=1);

namespace App\Http\Presenters;

use App\Models\PollQuestion;
use Illuminate\Database\Eloquent\Collection;

final class PollQuestionArrayPresenter
{
    public function __construct(
        private PollAnswerArrayPresenter $pollAnswerArrayPresenter,
        private QuestionTypeArrayPresenter $questionTypeArrayPresenter,
    ) {
    }

    public function present(PollQuestion $question): array
    {
        return [
            'id' => $question->getId(),
            'name' => $question->getName(),
            'required' => $question->getRequired(),
            'type' => $question->getType(),
            'pollId' => $question->getPollId(),
            'answers' => $this->pollAnswerArrayPresenter->presentCollections($question->getAnswers()),
        ];
    }

    public function presentCollections(Collection $questions): array
    {
        return $questions
            ->map(
                function (PollQuestion $question) {
                    return $this->present($question);
                }
            )
            ->all();
    }
}
