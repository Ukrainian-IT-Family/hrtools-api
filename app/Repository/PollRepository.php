<?php

declare(strict_types=1);

namespace App\Repository;

use App\Models\Poll;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class PollRepository implements PaginableInterface
{
    public function create(array $fields): Poll
    {
        return Poll::create($fields);
    }

    /**
     * @param  int  $id
     * @return Poll
     *
     * @throws ModelNotFoundException
     */
    public function getById(int $id): Poll
    {
        return Poll::findOrFail($id);
    }

    public function getPollsCreatedByUser(int $userId): Collection
    {
        return Poll::query()->where([['created_by', $userId]])->orderBy('status', 'asc')->orderByDesc('created_at')->get();
    }

    public function getPaginatedCreatedByUser(
        int $userId,
        int $page = self::DEFAULT_PAGE,
        int $perPage = self::DEFAULT_PER_PAGE,
        string $sort = self::DEFAULT_SORT,
        string $direction = self::DEFAULT_DIRECTION
    ): LengthAwarePaginator {
        return Poll::where([['created_by', $userId]])
            ->orderBy($sort, $direction)->paginate(
                $perPage,
                ['*'],
                null,
                $page
            );
    }

    public function getLatestPollsCreatedByUser(int $userId, int $count = Poll::DEFAULT_LATEST_COUNT): Collection
    {
        return Poll::query()->where([['created_by', $userId]])->orderByDesc('created_at')->limit($count)->get();
    }

    public function getPollsForAdmin(int $userId): Collection
    {
        $admins = User::where([['role_id', 1]])->whereNot([['id', $userId]])->pluck('id', 'id');

        return Poll::whereNotIn('created_by', $admins)->orderBy('status', 'asc')->orderByDesc('created_at')->get();
    }

    public function getPaginatedForAdmin(
        array $adminsIds,
        int $page = self::DEFAULT_PAGE,
        int $perPage = self::DEFAULT_PER_PAGE,
        string $sort = self::DEFAULT_SORT,
        string $direction = self::DEFAULT_DIRECTION
    ): LengthAwarePaginator {
        return Poll::whereNotIn('created_by', $adminsIds)
            ->orderBy($sort, $direction)->paginate(
                $perPage,
                ['*'],
                null,
                $page
            );
    }

    public function getLatestPollsForAdmin(int $userId, int $count = Poll::DEFAULT_LATEST_COUNT): Collection
    {
        $admins = User::where([['role_id', User::ADMIN_ROLE_ID]])->whereNot([['id', $userId]])->pluck('id', 'id');

        return Poll::whereNotIn('created_by', $admins)->orderByDesc('created_at')->limit($count)->get();
    }

    public function getPollsForUser(int $userId): Collection
    {
        $user = User::find($userId);

        return $user->polls()->where('status', Poll::ACTIVE_STATUS_ID)->orderBy('created_at', 'DESC')->get();
    }

    public function getLatestPollsForUser(User $user, int $count = Poll::DEFAULT_LATEST_COUNT): Collection
    {
        return $user->polls()->where('status', Poll::ACTIVE_STATUS_ID)->orderBy('created_at', 'DESC')->limit($count)->get();
    }

    public function completePoll(Poll $poll): Poll
    {
        $poll->status = Poll::COMPLETE_STATUS_ID;
        $poll->save();

        return $poll;
    }

    public function isComplete(Poll $poll): bool
    {
        return $poll->poll_result_count == $poll->workers_count;
    }

    public function setWorkersForPoll(Poll $poll, array $workers): Poll
    {
        $poll->workers()->sync($workers);

        return $poll;
    }

    public function getWorkersWhichNotCompletePoll(Poll $poll): Collection
    {
        return $poll->workers()->wheredoesntHave('pollResult',
            function (Builder $query) use ($poll) {
                $query->where('poll_id', $poll->getId());
            }
        )->get();
    }

    public function isWorkerBelongToPoll(int $pollId, int $userId): bool
    {
        return Poll::find($pollId)->workers()->where('user_id', $userId)->exists();
    }

    public function save(Poll $poll): Poll
    {
        $poll->save();

        return $poll;
    }

    public function delete($id): ?bool
    {
        return Poll::find($id)->delete();
    }

    public function paginate(
        int $page = self::DEFAULT_PAGE,
        int $perPage = self::DEFAULT_PER_PAGE,
        string $sort = self::DEFAULT_SORT,
        string $direction = self::DEFAULT_DIRECTION
    ): LengthAwarePaginator {
        return Poll::orderBy($sort, $direction)->paginate($perPage, ['*'], null, $page);
    }
}
