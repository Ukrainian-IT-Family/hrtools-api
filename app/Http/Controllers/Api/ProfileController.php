<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\Resume\DeleteProfileResumeAction;
use App\Actions\Resume\UploadProfileResumeAction;
use App\Actions\Resume\UploadProfileResumeRequest;
use App\Actions\User\GetAuthenticatedUserAction;
use App\Actions\User\UpdateProfileAction;
use App\Actions\User\UpdateProfileRequest;
use App\Actions\User\UploadProfileImageAction;
use App\Actions\User\UploadProfileImageRequest;
use App\Http\Presenters\ResumeArrayPresenter;
use App\Http\Presenters\UserArrayPresenter;
use App\Http\Requests\Api\User\UpdateProfileValidationRequest;
use App\Http\Requests\Api\User\UploadProfileImageValidationRequest;
use App\Http\Requests\Api\User\UploadProfileResumeValidationRequest;
use Illuminate\Http\JsonResponse;

final class ProfileController extends ApiController
{
    public function me(GetAuthenticatedUserAction $action, UserArrayPresenter $userArrayPresenter): JsonResponse
    {
        $response = $action->execute();

        return $this->successResponse($userArrayPresenter->present($response->getUser()));
    }

    public function uploadProfileImage(
        UploadProfileImageValidationRequest $validationRequest,
        UploadProfileImageAction $action,
        UserArrayPresenter $presenter
    ): JsonResponse {
        $response = $action->execute(
            new UploadProfileImageRequest(
                $validationRequest->file('image')
            )
        );

        return $this->successResponse($presenter->present($response->getUser()));
    }

    public function uploadProfileResume(
        UploadProfileResumeValidationRequest $validationRequest,
        UploadProfileResumeAction $action,
        ResumeArrayPresenter $presenter
    ): JsonResponse {
        $response = $action->execute(
            new UploadProfileResumeRequest(
                $validationRequest->file('resume')
            )
        );

        return $this->successResponse($presenter->present($response->getResume()));
    }

    public function deleteProfileResume(
        DeleteProfileResumeAction $action,
    ): JsonResponse {
        $action->execute();

        return $this->emptyResponse();
    }

    public function update(
        UpdateProfileValidationRequest $validationRequest,
        UpdateProfileAction $action,
        UserArrayPresenter $presenter
    ): JsonResponse {
        $response = $action->execute(
            new UpdateProfileRequest(
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
                $validationRequest->get('emergency')
            )
        );

        return $this->successResponse($presenter->present($response->getUser()));
    }
}
