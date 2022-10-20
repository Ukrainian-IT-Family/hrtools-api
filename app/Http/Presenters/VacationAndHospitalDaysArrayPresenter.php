<?php

declare(strict_types=1);

namespace App\Http\Presenters;

use App\Actions\VacationOrHospital\VacationAndHospitalDaysResponse;

final class VacationAndHospitalDaysArrayPresenter
{
    public function present(VacationAndHospitalDaysResponse $response): array
    {
        return [
            'availableVacationsDays' => $response->getAvailableVacationDays(),
            'vacationDaysUsed' => $response->getVacationDaysUsed(),
            'hospitalDaysUsed' => $response->getHospitalDaysUsed(),
        ];
    }
}
