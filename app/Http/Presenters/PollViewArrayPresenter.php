<?php

declare(strict_types=1);

namespace App\Http\Presenters;

use App\Actions\Poll\PollViewResponse;

final class PollViewArrayPresenter
{
    public function __construct(
        private PollQuestionArrayPresenter $questionArrayPresenter,
        private PollAnswerArrayPresenter $answerArrayPresenter
    ) {
    }

    public function present(PollViewResponse $response): array
    {
        return [
            'id' => $response->getPollId(),
            'title' => $response->getPollTitle(),
            'questions' => $this->questionArrayPresenter->presentCollections($response->getPollQuestions()),
            'result' => $this->answerArrayPresenter->presentCollections($response->getPollResultAnswers()),
        ];
    }
}
