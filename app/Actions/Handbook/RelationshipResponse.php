<?php

declare(strict_types=1);

namespace App\Actions\Handbook;

final class RelationshipResponse
{
    public function __construct(
        private object $relationship,
    ) {
    }

    public function getRelationshipName(): string
    {
        return $this->relationship->name;
    }

    public function getRelationshipId(): int
    {
        return $this->relationship->id;
    }
}
