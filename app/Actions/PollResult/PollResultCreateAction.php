<?php

declare(strict_types=1);

namespace App\Actions\PollResult;

use App\Exceptions\ForbiddenAccessToPollException;
use App\Notifications\PollCompleteNotification;
use App\Notifications\UserCompletePollNotification;
use App\Repository\PollAnswerRepository;
use App\Repository\PollRepository;
use App\Repository\PollResultRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

final class PollResultCreateAction
{
    public function __construct(
        private PollResultRepository $resultRepository,
        private PollAnswerRepository $answerRepository,
        private PollRepository $pollRepository
    ) {
    }

    public function execute(PollResultCreateRequest $request): PollResultResponse
    {
        $user = Auth::user();

        if (! $this->pollRepository->isWorkerBelongToPoll($request->getPollId(), $user->getId())) {
            throw new ForbiddenAccessToPollException();
        }

        $pollResult = $this->resultRepository->create([
            'poll_id' => $request->getPollId(),
            'user_id' => $user->getId(),
        ]);

        $userAnswers = $request->getAnswers();
        $pollAnswers = [];
        foreach ($userAnswers as $answer) {
            if ($this->answerRepository->exist((array_key_exists('id', $answer) ? $answer['id'] : 0))) {
                $pollAnswer = $this->answerRepository->getById($answer['id']);
            } else {
                $pollAnswer = $this->answerRepository->create(
                    [
                        'value' => $answer['value'],
                        'poll_question_id' => $answer['questionId'],
                    ]
                );
            }
            $pollAnswers[] = $pollAnswer->id;
        }
        $pollResult = $this->resultRepository->setAnswersForPollResult($pollResult, $pollAnswers);
        $pollResult = $this->resultRepository->save($pollResult);

        $poll = $this->pollRepository->getById($request->getPollId());

        if ($this->pollRepository->isComplete($poll)) {
            $this->pollRepository->completePoll($poll);
            Notification::send($poll->getAuthor(), new PollCompleteNotification($poll));
        }
        Notification::send($poll->getAuthor(), new UserCompletePollNotification($poll, $user));

        return new PollResultResponse($pollResult);
    }
}
