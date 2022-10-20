<?php

declare(strict_types=1);

namespace App\Actions\VacationOrHospital;

use App\Repository\VacationOrHospitalRepository;

final class VacationOrHospitalDeleteAction
{
    public function __construct(
        private VacationOrHospitalRepository $repository
    ) {
    }

    public function execute(VacationOrHospitalDeleteRequest $request): void
    {
        $this->repository->delete($request->getId());
    }
}
