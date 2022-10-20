<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\Poll\GetLatestPollsForAdminAction;
use App\Actions\Poll\GetPollCollectionRequest;
use App\Actions\Poll\GetPollsForAdminAction;
use App\Actions\Resume\DeleteUserResumeAction;
use App\Actions\Resume\DeleteUserResumeRequest;
use App\Actions\Resume\UploadUserResumeAction;
use App\Actions\Resume\UploadUserResumeRequest;
use App\Actions\User\DeleteUserAction;
use App\Actions\User\DeleteUserRequest;
use App\Actions\User\FindUserCollectionByFullNameAction;
use App\Actions\User\FindUserCollectionByFullNameRequest;
use App\Actions\User\GetUserCollectionAction;
use App\Actions\User\GetWorkerCollectionAction;
use App\Actions\User\UpdateUserAction;
use App\Actions\User\UpdateUserRequest;
use App\Actions\VacationOrHospital\GetLatestVacationOrHospitalCollectionForAdminAction;
use App\Actions\VacationOrHospital\GetVacationOrHospitalCollectionForAdminAction;
use App\Actions\VacationOrHospital\GetVacationOrHospitalCollectionRequest;
use App\Http\Presenters\PollArrayPresenter;
use App\Http\Presenters\ResumeArrayPresenter;
use App\Http\Presenters\UserArrayPresenter;
use App\Http\Presenters\VacationOrHospitalArrayPresenter;
use App\Http\Requests\Api\CollectionValidateRequest;
use App\Http\Requests\Api\User\FindUsersValidationRequest;
use App\Http\Requests\Api\User\UpdateUserValidationRequest;
use App\Http\Requests\Api\User\UploadUserResumeValidationRequest;
use Illuminate\Http\JsonResponse;

final class AdminController extends ApiController
{
    public function getAllUsers(
        GetUserCollectionAction $action,
        UserArrayPresenter $presenter
    ): JsonResponse {
        $response = $action->execute();

        return $this->successResponse($presenter->getCollections($response));
    }

    public function getWorkers(
        GetWorkerCollectionAction $action,
        UserArrayPresenter $presenter
    ): JsonResponse {
        $response = $action->execute();

        return $this->successResponse($presenter->getShortInfoCollections($response));
    }

    public function getUsersByFullName(
        FindUsersValidationRequest $request,
        FindUserCollectionByFullNameAction $action,
        UserArrayPresenter $presenter
    ): JsonResponse {
        $response = $action->execute(
            new FindUserCollectionByFullNameRequest(
                $request->get('fullName')
            )
        );

        return $this->successResponse($presenter->getCollections($response));
    }

    public function deleteUserResume(
        $userId,
        DeleteUserResumeAction $action,
    ): JsonResponse {
        $action->execute(
            new DeleteUserResumeRequest(
                (int) $userId
            )
        );

        return $this->emptyResponse();
    }

    public function deleteUser(
        string $id,
        DeleteUserAction $action,
    ): JsonResponse {
        $action->execute(
            new DeleteUserRequest(
                (int) $id
            )
        );

        return $this->emptyResponse();
    }

    public function uploadUserResume(
        $userId,
        UploadUserResumeValidationRequest $validationRequest,
        UploadUserResumeAction $action,
        ResumeArrayPresenter $presenter
    ): JsonResponse {
        $response = $action->execute(
            new UploadUserResumeRequest(
                (int) $userId,
                $validationRequest->file('resume')
            )
        );

        return $this->successResponse($presenter->present($response->getResume()));
    }

    public function updateUser(
        $userId,
        UpdateUserValidationRequest $validationRequest,
        UpdateUserAction $action,
        UserArrayPresenter $presenter
    ): JsonResponse {
        $response = $action->execute(
            new UpdateUserRequest(
                (int) $userId,
                $validationRequest->get('firstName'),
                $validationRequest->get('middleName'),
                $validationRequest->get('lastName'),
                $validationRequest->get('birthday'),
                $validationRequest->boolean('gender'),
                $validationRequest->get('maritalStatus'),
                $validationRequest->get('children'),
                $validationRequest->get('region'),
                $validationRequest->get('area'),
                $validationRequest->get('town'),
                $validationRequest->get('postOffice'),
                $validationRequest->get('contactsPhones'),
                $validationRequest->get('email'),
                $validationRequest->get('linkedin'),
                $validationRequest->get('facebook'),
                $validationRequest->get('emergency'),
                $validationRequest->get('role'),
                $validationRequest->get('workers'),
                $validationRequest->get('workTime'),
                $validationRequest->get('position'),
                $validationRequest->get('hireDate')
            )
        );

        return $this->successResponse($presenter->present($response->getUser()));
    }

    public function getPollsForAdmin(
        CollectionValidateRequest $validateRequest,
        GetPollsForAdminAction $action,
        PollArrayPresenter $presenter
    ): JsonResponse {
        $response = $action->execute(new GetPollCollectionRequest(
            (int) $validateRequest->query('page'), $validateRequest->query('sort'), $validateRequest->query('direction'))
        );

        return  $this->createPaginatedResponse($response->getPaginator(), $presenter);
    }

    public function getLatestPollsForAdmin(
        GetLatestPollsForAdminAction $action,
        PollArrayPresenter $presenter
    ): JsonResponse {
        $response = $action->execute();

        return $this->successResponse($presenter->presentCollection($response->getPolls()));
    }

    public function getVacationOrHospitalRequests(
        CollectionValidateRequest $validateRequest,
        GetVacationOrHospitalCollectionForAdminAction $action,
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

    public function getLatestVacationOrHospitalRequests(
        GetLatestVacationOrHospitalCollectionForAdminAction $action,
        VacationOrHospitalArrayPresenter $presenter
    ): JsonResponse {
        $response = $action->execute();

        return $this->successResponse($presenter->presentCollection($response->getVacations()));
    }
}
