<?php

declare(strict_types=1);

namespace App\Actions\VacationOrHospital;

use App\Actions\PaginatedResponse;
use App\Repository\UserRepository;
use App\Repository\VacationOrHospitalRepository;

final class GetVacationOrHospitalCollectionForAdminAction
{
    public function __construct(
        private VacationOrHospitalRepository $repository,
        private UserRepository $userRepository
    ) {
    }

    public function execute(GetVacationOrHospitalCollectionRequest $request): PaginatedResponse
    {
        $hrs = $this->userRepository->getHrsList();
        $hrIds = collect($hrs)->pluck('id', 'id')->toArray();
        $paginator = $this->repository->getPaginatedForAdmin(
            $hrIds,
            $request->getPage() ?: VacationOrHospitalRepository::DEFAULT_PAGE,
            VacationOrHospitalRepository::DEFAULT_PER_PAGE,
        );

        return new PaginatedResponse($paginator);
    }
}
