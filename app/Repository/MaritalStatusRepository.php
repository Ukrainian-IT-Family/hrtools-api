<?php

declare(strict_types=1);

namespace App\Repository;

use App\Models\MaritalStatus;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class MaritalStatusRepository
{
    public function create(array $fields): MaritalStatus
    {
        return MaritalStatus::create($fields);
    }

    /**
     * @param  int  $id
     * @return MaritalStatus
     *
     * @throws ModelNotFoundException
     */
    public function getById(int $id): MaritalStatus
    {
        return MaritalStatus::findOrFail($id);
    }

    public function save(MaritalStatus $status): MaritalStatus
    {
        $status->save();

        return $status;
    }

    public function getAll(): Collection
    {
        return MaritalStatus::query()->orderBy('name', 'asc')->get();
    }
}
