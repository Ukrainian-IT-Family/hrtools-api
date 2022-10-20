<?php

declare(strict_types=1);

namespace App\Actions\VacationOrHospital;

final class VacationAndHospitalDaysResponse
{
    public function __construct(
        private int $availableVacationDays,
        private int $vacationDaysUsed,
        private int $hospitalDaysUsed
    ) {
    }

    public function getAvailableVacationDays(): int
    {
        return $this->availableVacationDays;
    }

    public function getVacationDaysUsed(): int
    {
        return $this->vacationDaysUsed;
    }

    public function getHospitalDaysUsed(): int
    {
        return $this->hospitalDaysUsed;
    }
}
