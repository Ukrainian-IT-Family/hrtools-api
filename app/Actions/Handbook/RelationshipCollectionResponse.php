<?php

declare(strict_types=1);

namespace App\Actions\Handbook;

use Illuminate\Database\Eloquent\Collection;

final class RelationshipCollectionResponse
{
    public function __construct(
        private $relationship,
    ) {
    }

    public function getRelationship(): Collection
    {
        return $this->relationship;
    }
}
