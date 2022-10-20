<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\Poll\GetLatestPollsForUserAction;
use App\Actions\Poll\GetPollsForUserAction;
use App\Actions\Poll\PollViewAction;
use App\Actions\Poll\PollViewRequest;
use App\Actions\PollResult\PollResultCreateAction;
use App\Actions\PollResult\PollResultCreateRequest;
use App\Http\Presenters\PollArrayPresenter;
use App\Http\Presenters\PollResultArrayPresenter;
use App\Http\Presenters\PollViewArrayPresenter;
use App\Http\Requests\Api\Poll\PollDoValidationRequest;
use Illuminate\Http\JsonResponse;

final class PollController extends ApiController
{
    public function getPollsForUser(
        GetPollsForUserAction $action,
        PollArrayPresenter $presenter
    ): JsonResponse {
        $response = $action->execute();

        return $this->successResponse($presenter->presentCollection($response->getPolls()));
    }

    public function getLatestForUser(
        GetLatestPollsForUserAction $action,
        PollArrayPresenter $presenter
    ): JsonResponse {
        $response = $action->execute();

        return $this->successResponse($presenter->presentCollection($response->getPolls()));
    }

    public function doPoll(
        $pollId,
        PollDoValidationRequest $validationRequest,
        PollResultCreateAction $action,
        PollResultArrayPresenter $presenter
    ): JsonResponse {
        $response = $action->execute(new PollResultCreateRequest(
            (int) $pollId,
            $validationRequest->get('answers')
        ));

        return $this->successResponse($presenter->present($response));
    }

    public function viewPollResult(
        $pollId,
        PollViewAction $action,
        PollViewArrayPresenter $presenter
    ): JsonResponse {
        $response = $action->execute(new PollViewRequest(
            (int) $pollId
        ));

        return $this->successResponse($presenter->present($response));
    }
}
