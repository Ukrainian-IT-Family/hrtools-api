<?php

declare(strict_types=1);

namespace App\Http\Presenters;

use App\Actions\VacationOrHospital\VacationOrHospitalResponse;
use App\Contracts\PresenterCollectionInterface;
use App\Models\VacationOrHospital;
use Illuminate\Support\Collection;

final class VacationOrHospitalArrayPresenter implements PresenterCollectionInterface
{
    public function __construct(
        private UserArrayPresenter $userArrayPresenter
    ) {
    }

    public function present(VacationOrHospitalResponse $response): array
    {
        return [
            'id' => $response->getVacationOrHospitalId(),
            'type' => $response->getVacationOrHospitalType(),
            'dateStart' => $response->getVacationOrHospitalDateStart(),
            'dateEnd' => $response->getVacationOrHospitalDateEnd(),
            'daysCount' => $response->getVacationOrHospitalDaysCount(),
            'status' => $response->getVacationOrHospitalStatus(),
            'comment' => $response->getVacationOrHospitalComment(),
            'dateCreate' => $response->getVacationOrHospitalDateCreate(),
            'userId' => $response->getVacationOrHospitalUserId(),
            'canDelete' => $response->canDelete(),
            'user' => $this->userArrayPresenter->shortInfo($response->getVacationOrHospitalUser()),
        ];
    }

    public function presentCollection(Collection $collection): array
    {
        return $collection
            ->map(
                function (VacationOrHospital $vacationOrHospital) {
                    return $this->present(new VacationOrHospitalResponse($vacationOrHospital));
                }
            )
            ->all();
    }
}
