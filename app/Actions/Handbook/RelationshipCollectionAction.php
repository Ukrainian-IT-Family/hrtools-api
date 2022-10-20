<?php

declare(strict_types=1);

namespace App\Actions\Handbook;

use App\Repository\RelationshipRepository;

final class RelationshipCollectionAction
{
    public function __construct(private RelationshipRepository $relationshipRepository)
    {
    }

    public function execute(): RelationshipCollectionResponse
    {
        $relationships = $this->relationshipRepository->getAll();

        return new RelationshipCollectionResponse($relationships);
    }
}
