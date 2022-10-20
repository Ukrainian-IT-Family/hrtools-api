<?php

declare(strict_types=1);

namespace App\Actions\VacationOrHospital;

use App\Repository\VacationOrHospitalRepository;
use Illuminate\Support\Facades\Auth;

final class GetLatestVacationOrHospitalForUserAction
{
    public function __construct(
        private VacationOrHospitalRepository $repository
    ) {
    }

    public function execute(): VacationOrHospitalCollectionResponse
    {
        $userId = Auth::id();
        $response = $this->repository->getLatestForUser($userId);

        return new VacationOrHospitalCollectionResponse($response);
    }
}
