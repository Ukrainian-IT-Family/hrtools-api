<?php

declare(strict_types=1);

namespace App\Actions\VacationOrHospital;

use App\Models\VacationOrHospital;
use App\Notifications\HospitalRequestAcceptedNotification;
use App\Notifications\VacationRequestAcceptedNotification;
use App\Repository\VacationOrHospitalRepository;
use Illuminate\Support\Facades\Notification;

final class VacationOrHospitalAcceptAction
{
    public function __construct(
        private VacationOrHospitalRepository $repository
    ) {
    }

    public function execute(VacationOrHospitalAcceptRequest $request): VacationOrHospitalResponse
    {
        $vacationOrHospital = $this->repository->getById($request->getId());
        $vacationOrHospital->status = VacationOrHospital::ACCEPT_STATUS;
        $vacationOrHospital = $this->repository->save($vacationOrHospital);

        if ($vacationOrHospital->getType() == VacationOrHospital::VACATION_ID) {
            Notification::send($vacationOrHospital->getAuthor(), new VacationRequestAcceptedNotification($vacationOrHospital));
        } elseif ($vacationOrHospital->getType() == VacationOrHospital::HOSPITAL_ID) {
            Notification::send($vacationOrHospital->getAuthor(), new HospitalRequestAcceptedNotification($vacationOrHospital));
        }

        return new VacationOrHospitalResponse($vacationOrHospital);
    }
}
