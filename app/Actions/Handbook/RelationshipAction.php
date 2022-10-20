<?php

declare(strict_types=1);

namespace App\Actions\Handbook;

use App\Repository\RelationshipRepository;

final class RelationshipAction
{
    public function __construct(private RelationshipRepository $relationshipRepository)
    {
    }

    public function execute(RelationshipRequest $request): RelationshipResponse
    {
        $relationshipId = $request->getRelationshipId();
        $status = $this->relationshipRepository->getById($relationshipId);

        return new RelationshipResponse($status);
    }
}
