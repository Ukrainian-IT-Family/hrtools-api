<?php

declare(strict_types=1);

namespace App\Http\Presenters;

use App\Actions\PollResult\PollResultResponse;
use App\Models\PollResult;
use Illuminate\Database\Eloquent\Collection;

final class PollResultArrayPresenter
{
    public function __construct(
        private UserArrayPresenter $userArrayPresenter,
        private PollAnswerArrayPresenter $pollAnswerArrayPresenter
    ) {
    }

    public function present(PollResultResponse $result): array
    {
        return [
            'id' => $result->getPollResultId(),
            'userId' => $result->getPollResultUserId(),
            'user' => $this->userArrayPresenter->shortInfo($result->getPollResultUser()),
            'answers' => $this->pollAnswerArrayPresenter->presentCollectionsGroupByQuestions($result->getPollResultAnswers()),
        ];
    }

    public function presentCollections(Collection $results): array
    {
        return $results
            ->map(
                function (PollResult $pollResult) {
                    return $this->present(new PollResultResponse($pollResult));
                }
            )
            ->all();
    }
}
