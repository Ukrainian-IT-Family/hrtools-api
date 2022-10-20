<?php

declare(strict_types=1);

namespace App\Repository;

use App\Models\VacationOrHospital;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

final class VacationOrHospitalRepository implements PaginableInterface
{
    public function create(array $fields): VacationOrHospital
    {
        return VacationOrHospital::create($fields);
    }

    public function getById(int $id): VacationOrHospital
    {
        return VacationOrHospital::findOrFail($id);
    }

    public function save(VacationOrHospital $vacationOrHospital): VacationOrHospital
    {
        $vacationOrHospital->save();

        return $vacationOrHospital;
    }

    public function getPaginatedForAdmin(
        array $hrIds,
        int $page = self::DEFAULT_PAGE,
        int $perPage = self::DEFAULT_PER_PAGE,
        string $sort = 'date_start',
        string $direction = self::DEFAULT_DIRECTION
    ): LengthAwarePaginator {
        return VacationOrHospital::whereIn('user_id', $hrIds)->orderBy($sort, $direction)->paginate(
            $perPage,
            ['*'],
            null,
            $page
        );
    }

    public function getLatestForAdmin(
        array $hrIds,
        int $count = VacationOrHospital::DEFAULT_LATEST_COUNT
    ): Collection {
        return VacationOrHospital::whereIn('user_id', $hrIds)->orderByDesc('created_at')->limit($count)->get();
    }

    public function getPaginatedForHR(
        array $workersIds,
        int $page = self::DEFAULT_PAGE,
        int $perPage = 4,
        string $sort = 'date_start',
        string $direction = self::DEFAULT_DIRECTION
    ): LengthAwarePaginator {
        return VacationOrHospital::whereIn('user_id', $workersIds)
            ->orderBy($sort, $direction)->paginate(
            $perPage,
            ['*'],
            null,
            $page
        );
    }

    public function getLatestForHR(
        array $workersIds,
        int $count = VacationOrHospital::DEFAULT_LATEST_COUNT
    ): Collection {
        return VacationOrHospital::whereIn('user_id', $workersIds)->orderByDesc('created_at')->limit($count)->get();
    }

    public function getPaginatedByUserId(
        int $userId,
        int $page = self::DEFAULT_PAGE,
        int $perPage = self::DEFAULT_PER_PAGE,
        string $sort = 'date_start',
        string $direction = self::DEFAULT_DIRECTION
    ): LengthAwarePaginator {
        return VacationOrHospital::where('user_id', $userId)->orderBy($sort, $direction)->paginate(
            $perPage,
            ['*'],
            null,
            $page
        );
    }

    public function getLatestForUser(
        int $userId,
        int $count = VacationOrHospital::DEFAULT_LATEST_COUNT
    ): Collection {
        return VacationOrHospital::where('user_id', $userId)->orderByDesc('created_at')->limit($count)->get();
    }

    public function getNumberVacationDaysUsed(int $userId, Carbon $startDate): int
    {
        return (int) VacationOrHospital::where(
            [
                ['user_id', $userId],
                ['type', VacationOrHospital::VACATION_ID],
                ['status', VacationOrHospital::ACCEPT_STATUS],
            ]
        )
            ->whereBetween(
                'date_start',
                [$startDate->format('Y-m-d'), $startDate->addYears(1)->format('Y-m-d')]
            )
            ->sum('days_count');
    }

    public function getNumberHospitalDaysUsed(int $userId, Carbon $startDate): int
    {
        return (int) VacationOrHospital::where(
            [
                ['user_id', $userId],
                ['type', VacationOrHospital::HOSPITAL_ID],
                ['status', VacationOrHospital::ACCEPT_STATUS],
            ]
        )
            ->whereBetween(
                'date_start',
                [$startDate->format('Y-m-d'), $startDate->addYears(1)->format('Y-m-d')]
            )
            ->sum('days_count');
    }

    public function getNumberVacationDaysPending(int $userId, Carbon $startDate): int
    {
        return (int) VacationOrHospital::where(
            [
                ['user_id', $userId],
                ['type', VacationOrHospital::VACATION_ID],
                ['status', VacationOrHospital::PENDING_STATUS],
            ]
        )
            ->whereBetween(
                'date_start',
                [$startDate->format('Y-m-d'), $startDate->addYears(1)->format('Y-m-d')]
            )
            ->sum('days_count');
    }

    public function delete($id): ?bool
    {
        return VacationOrHospital::find($id)->delete();
    }

    public function paginate(
        int $page = self::DEFAULT_PAGE,
        int $perPage = self::DEFAULT_PER_PAGE,
        string $sort = 'date_start',
        string $direction = self::DEFAULT_DIRECTION
    ): LengthAwarePaginator {
        return VacationOrHospital::orderBy($sort, $direction)->paginate($perPage, ['*'], null, $page);
    }
}
