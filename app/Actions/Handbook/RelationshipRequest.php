<?php

declare(strict_types=1);

namespace App\Actions\Handbook;

final class RelationshipRequest
{
    public function __construct(
        private int $relationshipId,
    ) {
    }

    public function getRelationshipId(): int
    {
        return $this->relationshipId;
    }
}
