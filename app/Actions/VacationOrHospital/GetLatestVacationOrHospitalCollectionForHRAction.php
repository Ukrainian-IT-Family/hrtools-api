<?php

declare(strict_types=1);

namespace App\Actions\VacationOrHospital;

use App\Repository\UserRepository;
use App\Repository\VacationOrHospitalRepository;
use Illuminate\Support\Facades\Auth;

final class GetLatestVacationOrHospitalCollectionForHRAction
{
    public function __construct(
        private VacationOrHospitalRepository $repository,
        private UserRepository $userRepository
    ) {
    }

    public function execute(): VacationOrHospitalCollectionResponse
    {
        $managerId = Auth::id();
        $workers = $this->userRepository->getUsersByHR($managerId);
        $workerIds = collect($workers)->pluck('id', 'id')->toArray();

        $response = $this->repository->getLatestForHR($workerIds);

        return new VacationOrHospitalCollectionResponse($response);
    }
}
