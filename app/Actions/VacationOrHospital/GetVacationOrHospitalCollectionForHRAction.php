<?php

declare(strict_types=1);

namespace App\Actions\VacationOrHospital;

use App\Actions\PaginatedResponse;
use App\Repository\UserRepository;
use App\Repository\VacationOrHospitalRepository;
use Illuminate\Support\Facades\Auth;

final class GetVacationOrHospitalCollectionForHRAction
{
    public function __construct(
        private VacationOrHospitalRepository $repository,
        private UserRepository $userRepository
    ) {
    }

    public function execute(GetVacationOrHospitalCollectionRequest $request): PaginatedResponse
    {
        $managerId = Auth::id();
        $workers = $this->userRepository->getUsersByHR($managerId);
        $workerIds = collect($workers)->pluck('id', 'id')->toArray();

        $paginator = $this->repository->getPaginatedForHR(
            $workerIds,
            $request->getPage() ?: VacationOrHospitalRepository::DEFAULT_PAGE,
            VacationOrHospitalRepository::DEFAULT_PER_PAGE,
        );

        return new PaginatedResponse($paginator);
    }
}
