<?php

declare(strict_types=1);

namespace App\Actions\VacationOrHospital;

use App\Actions\PaginatedResponse;
use App\Repository\VacationOrHospitalRepository;
use Illuminate\Support\Facades\Auth;

final class GetVacationOrHospitalCollectionByUserIdAction
{
    public function __construct(
        private VacationOrHospitalRepository $repository
    ) {
    }

    public function execute(GetVacationOrHospitalCollectionRequest $request): PaginatedResponse
    {
        $userId = Auth::id();
        $paginator = $this->repository->getPaginatedByUserId(
            $userId,
            $request->getPage() ?: VacationOrHospitalRepository::DEFAULT_PAGE,
            VacationOrHospitalRepository::DEFAULT_PER_PAGE,
        );

        return new PaginatedResponse($paginator);
    }
}
