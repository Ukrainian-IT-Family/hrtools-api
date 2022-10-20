<?php

declare(strict_types=1);

namespace App\Repository;

use App\Models\PollQuestion;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class PollQuestionRepository
{
    public function create(array $fields): PollQuestion
    {
        return PollQuestion::create($fields);
    }

    /**
     * @param  int  $id
     * @return PollQuestion
     *
     * @throws ModelNotFoundException
     */
    public function getById(int $id): PollQuestion
    {
        return PollQuestion::findOrFail($id);
    }

    public function getQuestionsByPollId(int $pollId): array
    {
        return PollQuestion::where([['poll_id', $pollId]])->get()->toArray();
    }

    public function updateOrCreate(array $attribute, array $values): PollQuestion
    {
        return PollQuestion::updateOrCreate($attribute, $values);
    }

    public function save(PollQuestion $question): PollQuestion
    {
        $question->save();

        return $question;
    }

    public function delete($id): ?bool
    {
        return PollQuestion::find($id)->delete();
    }
}
