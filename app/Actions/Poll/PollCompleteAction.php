<?php

declare(strict_types=1);

namespace App\Actions\Poll;

use App\Notifications\PollCompleteByAdminNotification;
use App\Repository\PollRepository;
use Illuminate\Support\Facades\Notification;

final class PollCompleteAction
{
    public function __construct(private PollRepository $pollRepository)
    {
    }

    public function execute(PollCompleteRequest $request): PollResponse
    {
        $user = \Auth::user();

        $poll = $this->pollRepository->getById($request->getPollId());
        $poll = $this->pollRepository->completePoll($poll);

        if ($poll->getAuthorId() != $user->getId()) {
            Notification::send($poll->getAuthor(), new PollCompleteByAdminNotification($poll, $user));
        }

        return new PollResponse($poll);
    }
}
