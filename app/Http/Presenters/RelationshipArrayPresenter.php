<?php

declare(strict_types=1);

namespace App\Http\Presenters;

use App\Actions\Handbook\RelationshipCollectionResponse;
use App\Actions\Handbook\RelationshipResponse;
use App\Models\Relationship;

final class RelationshipArrayPresenter
{
    public function present(RelationshipResponse $relationship): array
    {
        return [
            'id' => $relationship->getRelationshipId(),
            'name' => $relationship->getRelationshipName(),
        ];
    }

    public function getCollections(RelationshipCollectionResponse $relationships): array
    {
        return $relationships->getRelationship()
            ->map(
                function (Relationship $relationship) {
                    return $this->present(new RelationshipResponse($relationship));
                }
            )
            ->all();
    }
}
