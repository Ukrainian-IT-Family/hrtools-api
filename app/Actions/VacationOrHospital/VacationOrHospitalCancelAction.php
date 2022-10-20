<?php

declare(strict_types=1);

namespace App\Actions\VacationOrHospital;

use App\Models\VacationOrHospital;
use App\Notifications\HospitalRequestCanceledNotification;
use App\Notifications\VacationRequestCanceledNotification;
use App\Repository\VacationOrHospitalRepository;
use Illuminate\Support\Facades\Notification;

final class VacationOrHospitalCancelAction
{
    public function __construct(
        private VacationOrHospitalRepository $repository
    ) {
    }

    public function execute(VacationOrHospitalCancelRequest $request): VacationOrHospitalResponse
    {
        $vacationOrHospital = $this->repository->getById($request->getId());
        $vacationOrHospital->status = VacationOrHospital::CANCEL_STATUS;
        $vacationOrHospital = $this->repository->save($vacationOrHospital);

        if ($vacationOrHospital->getType() == VacationOrHospital::VACATION_ID) {
            Notification::send($vacationOrHospital->getAuthor(), new VacationRequestCanceledNotification($vacationOrHospital));
        } elseif ($vacationOrHospital->getType() == VacationOrHospital::HOSPITAL_ID) {
            Notification::send($vacationOrHospital->getAuthor(), new HospitalRequestCanceledNotification($vacationOrHospital));
        }

        return new VacationOrHospitalResponse($vacationOrHospital);
    }
}
