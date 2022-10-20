<?php

namespace App\Http\Controllers\Api;

use App\Actions\Auth\RegisterAction;
use App\Actions\Auth\RegisterRequest;
use App\Http\Presenters\AuthenticationResponseArrayPresenter;
use App\Http\Requests\Api\Auth\RegisterValidationRequest;
use Illuminate\Http\JsonResponse;

class RegistrationController extends ApiController
{
    /**
     * Register a new user
     */
    public function register(
       RegisterValidationRequest $validationRequest,
       RegisterAction $action,
       AuthenticationResponseArrayPresenter $authenticationResponseArrayPresenter
    ): JsonResponse {
        $request = new RegisterRequest(
            $validationRequest->get('firstName'),
            $validationRequest->get('lastName'),
            $validationRequest->get('email'),
            $validationRequest->get('phone'),
            $validationRequest->get('password')
        );

        $response = $action->execute($request);

        return $this->successResponse($authenticationResponseArrayPresenter->present($response));
    }
}
