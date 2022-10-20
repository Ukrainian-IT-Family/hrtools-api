<?php

namespace App\Http\Controllers\Api;

use App\Actions\Role\RoleAction;
use App\Actions\Role\RoleCollectionAction;
use App\Actions\Role\RoleRequest;
use App\Http\Presenters\RoleArrayPresenter;
use Illuminate\Http\JsonResponse;

final class RoleController extends ApiController
{
    public function getRoles(
        RoleCollectionAction $action,
        RoleArrayPresenter $presenter
    ): JsonResponse {
        $response = $action->execute();

        return $this->successResponse($presenter->getCollection($response));
    }

    public function getRole(
        $id,
        RoleAction $action,
        RoleArrayPresenter $presenter
    ): JsonResponse {
        $response = $action->execute(
            new RoleRequest(
                (int) $id
            )
        );

        return $this->successResponse($presenter->present($response));
    }
}
