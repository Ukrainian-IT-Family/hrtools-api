<?php

declare(strict_types=1);

namespace App\Actions\VacationOrHospital;

use App\Repository\VacationOrHospitalRepository;
use Carbon\Carbon;

final class GetAvailableVacationDaysAction
{
    public function __construct(private VacationOrHospitalRepository $repository)
    {
    }

    public function execute(int $userId, string $hireDate): int
    {
        $startDate = Carbon::parse($hireDate);
        $now = Carbon::now();

        $fullWorkingYears = $startDate->diffInYears($now);

        if ($fullWorkingYears >= 1) {
            $startDate = $startDate->addYears($fullWorkingYears);
        }

        $numberOfMonthWorked = $startDate->diffInMonths($now);
        $vacationDaysUsed = $this->repository->getNumberVacationDaysUsed($userId, $startDate);

        return $numberOfMonthWorked * 2 - $vacationDaysUsed;
    }
}
