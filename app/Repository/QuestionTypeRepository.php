<?php

declare(strict_types=1);

namespace App\Repository;

use App\Models\QuestionType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class QuestionTypeRepository
{
    public function create(array $fields): QuestionType
    {
        return QuestionType::create($fields);
    }

    /**
     * @param  int  $id
     * @return QuestionType
     *
     * @throws ModelNotFoundException
     */
    public function getById(int $id): QuestionType
    {
        return QuestionType::findOrFail($id);
    }

    public function save(QuestionType $type): QuestionType
    {
        $type->save();

        return $type;
    }

    public function getAll(): Collection
    {
        return QuestionType::query()->orderBy('id', 'asc')->get();
    }
}
