<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\Handbook\MaritalStatusAction;
use App\Actions\Handbook\MaritalStatusCollectionAction;
use App\Actions\Handbook\MaritalStatusRequest;
use App\Actions\Handbook\QuestionTypeAction;
use App\Actions\Handbook\QuestionTypeCollectionAction;
use App\Actions\Handbook\QuestionTypeRequest;
use App\Actions\Handbook\RelationshipAction;
use App\Actions\Handbook\RelationshipCollectionAction;
use App\Actions\Handbook\RelationshipRequest;
use App\Http\Presenters\MaritalStatusArrayPresenter;
use App\Http\Presenters\QuestionTypeArrayPresenter;
use App\Http\Presenters\RelationshipArrayPresenter;
use Illuminate\Http\JsonResponse;

final class HandbookController extends ApiController
{
    public function getMaritalStatuses(
        MaritalStatusCollectionAction $action,
        MaritalStatusArrayPresenter $presenter
    ): JsonResponse {
        $response = $action->execute();

        return $this->successResponse($presenter->getCollections($response));
    }

    public function getMaritalStatus(
        $id,
        MaritalStatusAction $action,
        MaritalStatusArrayPresenter $presenter
    ): JsonResponse {
        $response = $action->execute(
            new MaritalStatusRequest(
                (int) $id
            )
        );

        return $this->successResponse($presenter->present($response));
    }

    public function getRelationships(
        RelationshipCollectionAction $action,
        RelationshipArrayPresenter $presenter
    ): JsonResponse {
        $response = $action->execute();

        return $this->successResponse($presenter->getCollections($response));
    }

    public function getRelationship(
        $id,
        RelationshipAction $action,
        RelationshipArrayPresenter $presenter
    ): JsonResponse {
        $response = $action->execute(
            new RelationshipRequest(
                (int) $id
            )
        );

        return $this->successResponse($presenter->present($response));
    }

    public function getQuestionTypes(
        QuestionTypeCollectionAction $action,
        QuestionTypeArrayPresenter $presenter
    ): JsonResponse {
        $response = $action->execute();

        return $this->successResponse($presenter->presentCollections($response));
    }

    public function getQuestionType(
        $id,
        QuestionTypeAction $action,
        QuestionTypeArrayPresenter $presenter
    ): JsonResponse {
        $response = $action->execute(
            new QuestionTypeRequest(
                (int) $id
            )
        );

        return $this->successResponse($presenter->present($response));
    }
}
