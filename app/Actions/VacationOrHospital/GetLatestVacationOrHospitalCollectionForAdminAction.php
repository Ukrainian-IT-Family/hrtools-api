<?php

declare(strict_types=1);

namespace App\Actions\VacationOrHospital;

use App\Repository\UserRepository;
use App\Repository\VacationOrHospitalRepository;

final class GetLatestVacationOrHospitalCollectionForAdminAction
{
    public function __construct(
        private VacationOrHospitalRepository $repository,
        private UserRepository $userRepository
    ) {
    }

    public function execute(): VacationOrHospitalCollectionResponse
    {
        $hrs = $this->userRepository->getHrsList();
        $hrIds = collect($hrs)->pluck('id', 'id')->toArray();
        $response = $this->repository->getLatestForAdmin($hrIds);

        return new VacationOrHospitalCollectionResponse($response);
    }
}
