<?php

declare(strict_types=1);

namespace App\Actions\Poll;

use App\Actions\PollResult\PollResultDeleteOldAction;
use App\Models\Poll;
use App\Notifications\NewPollNotification;
use App\Repository\PollRepository;
use Illuminate\Support\Facades\Notification;

final class PollSetWorkersAction
{
    public function __construct(
        private PollRepository $pollRepository,
        private PollResultDeleteOldAction $resultDeleteOldAction
    ) {
    }

    public function execute(PollSetWorkersRequest $request): PollResponse
    {
        $poll = $this->pollRepository->getById($request->getPollId());

        $workersIds = collect($request->getWorkers())->pluck('id', 'id')->toArray();
        $poll = $this->pollRepository->setWorkersForPoll($poll, $workersIds);

        $poll->status = Poll::ACTIVE_STATUS_ID;

        $poll = $this->pollRepository->save($poll);

        $this->resultDeleteOldAction->execute($request->getPollId(), $workersIds);

        $poll->refresh();

        $workers = $this->pollRepository->getWorkersWhichNotCompletePoll($poll);
        Notification::send($workers, new NewPollNotification($poll));

        return new PollResponse($poll);
    }
}
