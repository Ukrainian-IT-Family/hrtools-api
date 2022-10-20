<?php

declare(strict_types=1);

namespace App\Actions\VacationOrHospital;

use App\Exceptions\TooManyVacationDaysArePendingThanAvailableException;
use App\Models\VacationOrHospital;
use App\Notifications\HospitalRequestCreatedByHRNotification;
use App\Notifications\HospitalRequestCreatedByWorkerNotification;
use App\Notifications\VacationRequestCreatedByHRNotification;
use App\Notifications\VacationRequestCreatedByWorkerNotification;
use App\Repository\UserRepository;
use App\Repository\VacationOrHospitalRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

final class VacationOrHospitalCreateAction
{
    public function __construct(
        private VacationOrHospitalRepository $repository,
        private UserRepository $userRepository,
        private GetAvailableVacationDaysAction $availableVacationsDaysAction,
        private GetPendingVacationDaysAction $pendingVacationsDaysAction
    ) {
    }

    public function execute(VacationOrHospitalCreateRequest $request): VacationOrHospitalResponse
    {
        $user = Auth::user();
        $dateStart = Carbon::parse($request->getDateStart());
        $dateEnd = Carbon::parse($request->getDateEnd());
        $daysCount = $dateStart->diffInDays($dateEnd);

        if ((int) $request->isType() === VacationOrHospital::VACATION_ID) {
            $daysPending = ! is_null($user->getHireDate()) ? $this->pendingVacationsDaysAction->execute($user->getId(), $user->getHireDate()) : 0;
            $daysAvailable = ! is_null($user->getHireDate()) ? $this->availableVacationsDaysAction->execute($user->getId(), $user->getHireDate()) : 0;

            if ($daysPending + $daysCount > $daysAvailable) {
                throw new TooManyVacationDaysArePendingThanAvailableException();
            }
        }

        $vacationOrHospital = $this->repository->create([
            'type' => $request->isType(),
            'date_start' => $request->getDateStart(),
            'date_end' => $request->getDateEnd(),
            'days_count' => $daysCount,
            'status' => VacationOrHospital::PENDING_STATUS,
            'comment' => $request->getComment(),
            'user_id' => $user->getId(),
        ]);

        if ($vacationOrHospital->getType() === VacationOrHospital::VACATION_ID) {
            if ($user::isHR()) {
                $admins = $this->userRepository->getAdmins();
                Notification::send($admins, new VacationRequestCreatedByHRNotification($vacationOrHospital, $user));
            } elseif ($user::isWorker()) {
                Notification::send(
                    $user->getManager(),
                    new VacationRequestCreatedByWorkerNotification($vacationOrHospital, $user)
                );
            }
        } elseif ($vacationOrHospital->getType() === VacationOrHospital::HOSPITAL_ID) {
            if ($user::isHR()) {
                $admins = $this->userRepository->getAdmins();
                Notification::send($admins, new HospitalRequestCreatedByHRNotification($vacationOrHospital, $user));
            } elseif ($user::isWorker()) {
                Notification::send(
                    $user->getManager(),
                    new HospitalRequestCreatedByWorkerNotification($vacationOrHospital, $user)
                );
            }
        }

        return new VacationOrHospitalResponse($vacationOrHospital);
    }
}
