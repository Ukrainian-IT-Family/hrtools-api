<?php

declare(strict_types=1);

namespace App\Actions\Handbook;

use App\Repository\MaritalStatusRepository;

final class MaritalStatusCollectionAction
{
    public function __construct(private MaritalStatusRepository $maritalStatusRepository)
    {
    }

    public function execute(): MaritalStatusCollectionResponse
    {
        $maritalStatuses = $this->maritalStatusRepository->getAll();

        return new MaritalStatusCollectionResponse($maritalStatuses);
    }
}
