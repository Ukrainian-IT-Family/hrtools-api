<?php

declare(strict_types=1);

namespace App\Repository;

use App\Models\Resume;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class ResumeRepository
{
    public function create(array $fields): Resume
    {
        return Resume::create($fields);
    }

    /**
     * @param  int  $id
     * @return Resume
     *
     * @throws ModelNotFoundException
     */
    public function getById(int $id): Resume
    {
        return Resume::findOrFail($id);
    }

    public function getByUserId(int $user_id): ?Resume
    {
        return Resume::where([['user_id', $user_id]])->first();
    }

    public function updateOrCreate(array $attribute, array $values): Resume
    {
        return Resume::updateOrCreate($attribute, $values);
    }

    public function save(Resume $resume): Resume
    {
        $resume->save();

        return $resume;
    }

    public function delete(int $id): ?bool
    {
        return Resume::find($id)->delete();
    }
}
