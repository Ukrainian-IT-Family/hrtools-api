<?php

declare(strict_types=1);

namespace App\Http\Presenters;

use App\Models\PollAnswer;
use Illuminate\Database\Eloquent\Collection;

final class PollAnswerArrayPresenter
{
    public function __construct()
    {
    }

    public function present(PollAnswer $answer): array
    {
        return [
            'id' => $answer->getId(),
            'value' => $answer->getValue(),
            'questionId' => $answer->getQuestionId(),
        ];
    }

    public function presentCollections(Collection $answers): array
    {
        return $answers
            ->map(
                function (PollAnswer $answer) {
                    return $this->present($answer);
                }
            )
            ->all();
    }

    public function presentCollectionsGroupByQuestions(Collection $answers): array
    {
        return $answers
            ->map(
                function (PollAnswer $answer) {
                    return $this->present($answer);
                }
            )->groupBy('questionId')
            ->all();
    }
}
