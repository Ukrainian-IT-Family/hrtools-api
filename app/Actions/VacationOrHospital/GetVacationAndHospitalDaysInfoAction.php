<?php

declare(strict_types=1);

namespace App\Actions\VacationOrHospital;

use App\Repository\VacationOrHospitalRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

final class GetVacationAndHospitalDaysInfoAction
{
    public function __construct(
        private VacationOrHospitalRepository $repository,
        private GetAvailableVacationDaysAction $availableVacationDaysAction,
    ) {
    }

    public function execute(): VacationAndHospitalDaysResponse
    {
        $user = Auth::user();

        if (is_null($user->getHireDate())) {
            return new VacationAndHospitalDaysResponse(0, 0, 0);
        }

        $startDate = Carbon::parse($user->getHireDate());
        $fullWorkingYears = $startDate->diffInYears(Carbon::now());

        if ($fullWorkingYears >= 1) {
            $startDate->addYears($fullWorkingYears);
        }

        $availableVacationDays = $this->availableVacationDaysAction->execute($user->getId(), $user->getHireDate());
        $vacationDaysUsed = $this->repository->getNumberVacationDaysUsed($user->getId(), $startDate);
        $hospitalDaysUsed = $this->repository->getNumberHospitalDaysUsed($user->getId(), $startDate);

        return new VacationAndHospitalDaysResponse($availableVacationDays, $vacationDaysUsed, $hospitalDaysUsed);
    }
}
