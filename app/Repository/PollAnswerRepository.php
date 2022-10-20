<?php

declare(strict_types=1);

namespace App\Repository;

use App\Models\PollAnswer;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class PollAnswerRepository
{
    public function create(array $fields): PollAnswer
    {
        return PollAnswer::create($fields);
    }

    public function updateOrCreate(array $attribute, array $values): PollAnswer
    {
        return PollAnswer::updateOrCreate($attribute, $values);
    }

    /**
     * @param  int  $id
     * @return PollAnswer
     *
     * @throws ModelNotFoundException
     */
    public function getById(int $id): PollAnswer
    {
        return PollAnswer::findOrFail($id);
    }

    public function exist(?int $id): bool
    {
        return PollAnswer::where('id', $id)->exists();
    }

    public function getAnswersByQuestionId(int $questionId): array
    {
        return PollAnswer::where([['poll_question_id', $questionId]])->get()->toArray();
    }

    public function save(PollAnswer $answer): PollAnswer
    {
        $answer->save();

        return $answer;
    }

    public function delete($id): ?bool
    {
        return PollAnswer::find($id)->delete();
    }
}
