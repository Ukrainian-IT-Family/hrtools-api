<?php

declare(strict_types=1);

namespace App\Repository;

use App\Models\Child;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class ChildRepository
{
    public function create(array $fields): Child
    {
        return Child::create($fields);
    }

    /**
     * @param  int  $id
     * @return Child
     *
     * @throws ModelNotFoundException
     */
    public function getById(int $id): Child
    {
        return Child::findOrFail($id);
    }

    public function getByUserId(int $user_id): array
    {
        return Child::where([['user_id', $user_id]])->get()->toArray();
    }

    public function save(Child $child): Child
    {
        $child->save();

        return $child;
    }

    public function delete(int $id): ?bool
    {
        return Child::find($id)->delete();
    }
}
