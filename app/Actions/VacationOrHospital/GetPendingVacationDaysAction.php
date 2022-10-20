<?php

declare(strict_types=1);

namespace App\Actions\VacationOrHospital;

use App\Repository\VacationOrHospitalRepository;
use Carbon\Carbon;

final class GetPendingVacationDaysAction
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

        return $this->repository->getNumberVacationDaysPending($userId, $startDate);
    }
}
