<?php

declare(strict_types=1);

namespace App\Http\Presenters;

use App\Actions\Handbook\QuestionTypeCollectionResponse;
use App\Actions\Handbook\QuestionTypeResponse;
use App\Models\QuestionType;

final class QuestionTypeArrayPresenter
{
    public function present(QuestionTypeResponse $response): array
    {
        return [
            'id' => $response->getQuestionTypeId(),
            'name' => $response->getQuestionTypeName(),
        ];
    }

    public function presentCollections(QuestionTypeCollectionResponse $collectionResponse): array
    {
        return $collectionResponse->getQuestionType()
            ->map(
                function (QuestionType $type) {
                    return $this->present(new QuestionTypeResponse($type));
                }
            )
            ->all();
    }
}
