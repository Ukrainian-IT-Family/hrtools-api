<?php

declare(strict_types=1);

namespace App\Actions\Handbook;

use App\Repository\MaritalStatusRepository;

final class MaritalStatusAction
{
    public function __construct(private MaritalStatusRepository $maritalStatusRepository)
    {
    }

    public function execute(MaritalStatusRequest $request): MaritalStatusResponse
    {
        $statusId = $request->getMaritalStatusId();
        $status = $this->maritalStatusRepository->getById($statusId);

        return new MaritalStatusResponse($status);
    }
}
