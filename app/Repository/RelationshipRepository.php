<?php

declare(strict_types=1);

namespace App\Repository;

use App\Models\Relationship;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class RelationshipRepository
{
    public function create(array $fields): Relationship
    {
        return Relationship::create($fields);
    }

    /**
     * @param  int  $id
     * @return Relationship
     *
     * @throws ModelNotFoundException
     */
    public function getById(int $id): Relationship
    {
        return Relationship::findOrFail($id);
    }

    public function save(Relationship $relationship): Relationship
    {
        $relationship->save();

        return $relationship;
    }

    public function getAll(): Collection
    {
        return Relationship::query()->orderBy('name', 'asc')->get();
    }
}
