<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\Poll\GetLatestPollsByAuthorAction;
use App\Actions\Poll\GetPollCollectionRequest;
use App\Actions\Poll\GetPollsByAuthorAction;
use App\Actions\Poll\PollAction;
use App\Actions\Poll\PollCompleteAction;
use App\Actions\Poll\PollCompleteRequest;
use App\Actions\Poll\PollCreateAction;
use App\Actions\Poll\PollCreateRequest;
use App\Actions\Poll\PollDeleteAction;
use App\Actions\Poll\PollDeleteRequest;
use App\Actions\Poll\PollEditAction;
use App\Actions\Poll\PollEditRequest;
use App\Actions\Poll\PollRequest;
use App\Actions\Poll\PollSetWorkersAction;
use App\Actions\Poll\PollSetWorkersRequest;
use App\Actions\User\GetWorkerCollectionByHRAction;
use App\Actions\VacationOrHospital\GetLatestVacationOrHospitalCollectionForHRAction;
use App\Actions\VacationOrHospital\GetVacationOrHospitalCollectionForHRAction;
use App\Actions\VacationOrHospital\GetVacationOrHospitalCollectionRequest;
use App\Http\Presenters\PollArrayPresenter;
use App\Http\Presenters\UserArrayPresenter;
use App\Http\Presenters\VacationOrHospitalArrayPresenter;
use App\Http\Requests\Api\CollectionValidateRequest;
use App\Http\Requests\Api\Poll\PollCreateValidationRequest;
use App\Http\Requests\Api\Poll\PollEditValidationRequest;
use App\Http\Requests\Api\Poll\PollSetWorkersValidationRequest;
use Illuminate\Http\JsonResponse;

final class HRController extends ApiController
{
    public function createPoll(
        PollCreateValidationRequest $validationRequest,
        PollCreateAction $action,
        PollArrayPresenter $presenter
    ): JsonResponse {
        $request = new PollCreateRequest(
            $validationRequest->get('title'),
            $validationRequest->boolean('anonymous'),
            $validationRequest->get('questions')
        );
        $response = $action->execute($request);

        return $this->successResponse($presenter->present($response));
    }

    public function getPollsByAuthor(
        CollectionValidateRequest $validateRequest,
        GetPollsByAuthorAction $action,
        PollArrayPresenter $presenter
    ): JsonResponse {
        $response = $action->execute(
            new GetPollCollectionRequest(
                (int) $validateRequest->query('page'),
                $validateRequest->query('sort'),
                $validateRequest->query('direction')
            )
        );

        return $this->createPaginatedResponse($response->getPaginator(), $presenter);
    }

    public function getLatestPollsByAuthor(
        GetLatestPollsByAuthorAction $action,
        PollArrayPresenter $presenter
    ): JsonResponse {
        $response = $action->execute();

        return $this->successResponse($presenter->presentCollection($response->getPolls()));
    }

    public function deletePoll(
        $pollId,
        PollDeleteAction $action
    ): JsonResponse {
        $action->execute(new PollDeleteRequest((int) $pollId));

        return $this->emptyResponse();
    }

    public function sendPoll(
        $pollId,
        PollSetWorkersValidationRequest $validationRequest,
        PollSetWorkersAction $action,
        PollArrayPresenter $presenter
    ): JsonResponse {
        $response = $action->execute(
            new PollSetWorkersRequest(
                (int) $pollId,
                $validationRequest->get('workers')
            )
        );

        return $this->successResponse($presenter->present($response));
    }

    public function completePoll(
        $pollId,
        PollCompleteAction $action,
        PollArrayPresenter $presenter
    ): JsonResponse {
        $response = $action->execute(
            new PollCompleteRequest(
                (int) $pollId
            )
        );

        return $this->successResponse($presenter->present($response));
    }

    public function getPoll(
        $pollId,
        PollAction $action,
        PollArrayPresenter $presenter
    ): JsonResponse {
        $response = $action->execute(
            new PollRequest(
                (int) $pollId
            )
        );

        return $this->successResponse($presenter->present($response));
    }

    public function editPoll(
        $pollId,
        PollEditValidationRequest $validationRequest,
        PollEditAction $action,
        PollArrayPresenter $presenter
    ): JsonResponse {
        $response = $action->execute(
            new PollEditRequest(
                (int) $pollId,
                $validationRequest->get('title'),
                $validationRequest->boolean('anonymous'),
                $validationRequest->get('questions')
            )
        );

        return $this->successResponse($presenter->present($response));
    }

    public function getWorkersByHR(
        GetWorkerCollectionByHRAction $action,
        UserArrayPresenter $presenter
    ): JsonResponse {
        $response = $action->execute();

        return $this->successResponse($presenter->getShortInfoCollections($response));
    }

    public function getWorkerVacationOrHospitalRequests(
        CollectionValidateRequest $validateRequest,
        GetVacationOrHospitalCollectionForHRAction $action,
        VacationOrHospitalArrayPresenter $presenter
    ): JsonResponse {
        $response = $action->execute(
            new GetVacationOrHospitalCollectionRequest(
                (int) $validateRequest->query('page'),
                $validateRequest->query('sort'),
                $validateRequest->query('direction')
            )
        );

        return $this->createPaginatedResponse($response->getPaginator(), $presenter);
    }

    public function getLatestWorkerVacationOrHospitalRequests(
        GetLatestVacationOrHospitalCollectionForHRAction $action,
        VacationOrHospitalArrayPresenter $presenter
    ): JsonResponse {
        $response = $action->execute();

        return $this->successResponse($presenter->presentCollection($response->getVacations()));
    }
}
