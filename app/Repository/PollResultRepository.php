<?php

declare(strict_types=1);

namespace App\Repository;

use App\Models\PollResult;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class PollResultRepository
{
    public function create(array $fields): PollResult
    {
        return PollResult::create($fields);
    }

    /**
     * @param  int  $id
     * @return PollResult
     *
     * @throws ModelNotFoundException
     */
    public function getById(int $id): PollResult
    {
        return PollResult::findOrFail($id);
    }

    public function getResultsByPollId(int $pollId): Collection
    {
        return PollResult::where([['poll_id', $pollId]])->get();
    }

    public function getPollResultsWithOldWorkers(int $pollId, array $workers): Collection
    {
        return PollResult::query()->where([['poll_id', $pollId]])->whereNotIn('user_id', $workers)->get();
    }

    public function getResultByPollIdAndByUser(int $pollId, int $userId): PollResult
    {
        return PollResult::where([['poll_id', $pollId], ['user_id', $userId]])->first();
    }

    public function setAnswersForPollResult(PollResult $pollResult, array $pollAnswersIds): PollResult
    {
        $pollResult->pollAnswers()->sync($pollAnswersIds);

        return $pollResult;
    }

    public function save(PollResult $result): PollResult
    {
        $result->save();

        return $result;
    }

    public function delete($id): ?bool
    {
        return PollResult::find($id)->delete();
    }
}
