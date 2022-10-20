<?php

declare(strict_types=1);

namespace App\Http\Presenters;

use App\Actions\Poll\PollResponse;
use App\Actions\User\UserCollectionResponse;
use App\Contracts\PresenterCollectionInterface;
use App\Models\Poll;
use Illuminate\Support\Collection;

final class PollArrayPresenter implements PresenterCollectionInterface
{
    public function __construct(
        private UserArrayPresenter $userArrayPresenter,
        private PollResultArrayPresenter $resultArrayPresenter,
        private PollQuestionArrayPresenter $questionArrayPresenter
    ) {
    }

    public function present(PollResponse $poll): array
    {
        return [
            'id' => $poll->getPollId(),
            'title' => $poll->getPollTitle(),
            'status' => $poll->getPollStatus(),
            'anonymous' => $poll->getPollAnonymous(),
            'authorId' => $poll->getPollAuthorId(),
            'author' => $this->userArrayPresenter->shortInfo($poll->getPollAuthor()),
            'date' => $poll->getPollDate(),
            'passed' => $poll->isPassedByWorker(),
            'results' => $this->resultArrayPresenter->presentCollections($poll->getPollResults()),
            'resultCount' => $poll->getPollResultCount() ?? 0,
            'workersCount' => $poll->getPollWorkersCount() ?? 0,
            'workers' => $this->userArrayPresenter->getShortInfoCollections(
                new UserCollectionResponse($poll->getPollWorkers())
            ),
            'questions' => $this->questionArrayPresenter->presentCollections($poll->getPollQuestions()),
        ];
    }

    public function presentCollection(Collection $polls): array
    {
        return $polls
            ->map(
                function (Poll $poll) {
                    return $this->present(new PollResponse($poll));
                }
            )
            ->all();
    }
}
