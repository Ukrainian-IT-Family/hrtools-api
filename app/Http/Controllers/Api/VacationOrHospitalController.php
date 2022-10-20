<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\VacationOrHospital\GetLatestVacationOrHospitalForUserAction;
use App\Actions\VacationOrHospital\GetVacationAndHospitalDaysInfoAction;
use App\Actions\VacationOrHospital\GetVacationOrHospitalCollectionByUserIdAction;
use App\Actions\VacationOrHospital\GetVacationOrHospitalCollectionRequest;
use App\Actions\VacationOrHospital\VacationOrHospitalAcceptAction;
use App\Actions\VacationOrHospital\VacationOrHospitalAcceptRequest;
use App\Actions\VacationOrHospital\VacationOrHospitalCancelAction;
use App\Actions\VacationOrHospital\VacationOrHospitalCancelRequest;
use App\Actions\VacationOrHospital\VacationOrHospitalCreateAction;
use App\Actions\VacationOrHospital\VacationOrHospitalCreateRequest;
use App\Actions\VacationOrHospital\VacationOrHospitalDeleteAction;
use App\Actions\VacationOrHospital\VacationOrHospitalDeleteRequest;
use App\Http\Presenters\VacationAndHospitalDaysArrayPresenter;
use App\Http\Presenters\VacationOrHospitalArrayPresenter;
use App\Http\Requests\Api\CollectionValidateRequest;
use App\Http\Requests\Api\VacationOrHospital\VacationOrHospitalCreateValidationRequest;
use Illuminate\Http\JsonResponse;

final class VacationOrHospitalController extends ApiController
{
    public function createRequest(
        VacationOrHospitalCreateValidationRequest $validationRequest,
        VacationOrHospitalCreateAction $action,
        VacationOrHospitalArrayPresenter $presenter
    ): JsonResponse {
        $response = $action->execute(
            new VacationOrHospitalCreateRequest(
                $validationRequest->boolean('type'),
                $validationRequest->get('dateStart'),
                $validationRequest->get('dateEnd'),
                $validationRequest->get('comment'),
        ));

        return $this->successResponse($presenter->present($response));
    }

    public function getCollectionForUser(
        CollectionValidateRequest $validateRequest,
        GetVacationOrHospitalCollectionByUserIdAction $action,
        VacationOrHospitalArrayPresenter $presenter
    ): JsonResponse {
        $response = $action->execute(
            new GetVacationOrHospitalCollectionRequest(
                (int) $validateRequest->query('page'), $validateRequest->query('sort'), $validateRequest->query('direction')
            )
        );

        return $this->createPaginatedResponse($response->getPaginator(), $presenter);
    }

    public function getLatestForUser(
        GetLatestVacationOrHospitalForUserAction $action,
        VacationOrHospitalArrayPresenter $presenter
    ): JsonResponse {
        $response = $action->execute();

        return $this->successResponse($presenter->presentCollection($response->getVacations()));
    }

    public function getVacationAndHospitalDays(
        GetVacationAndHospitalDaysInfoAction $action,
        VacationAndHospitalDaysArrayPresenter $presenter
    ): JsonResponse {
        $response = $action->execute();

        return $this->successResponse($presenter->present($response));
    }

    public function accept(
        $id,
        VacationOrHospitalAcceptAction $action,
        VacationOrHospitalArrayPresenter $presenter
    ): JsonResponse {
        $response = $action->execute(new VacationOrHospitalAcceptRequest((int) $id));

        return $this->successResponse($presenter->present($response));
    }

    public function cancel(
        $id,
        VacationOrHospitalCancelAction $action,
        VacationOrHospitalArrayPresenter $presenter
    ): JsonResponse {
        $response = $action->execute(new VacationOrHospitalCancelRequest((int) $id));

        return $this->successResponse($presenter->present($response));
    }

    public function deleteRequest(
        $vacationOrHospitalId,
        VacationOrHospitalDeleteAction $action,
    ): JsonResponse {
        $action->execute(new VacationOrHospitalDeleteRequest((int) $vacationOrHospitalId));

        return $this->emptyResponse();
    }
}
