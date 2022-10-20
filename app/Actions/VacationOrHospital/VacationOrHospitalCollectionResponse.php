<?php

declare(strict_types=1);

namespace App\Actions\VacationOrHospital;

use Illuminate\Database\Eloquent\Collection;

final class VacationOrHospitalCollectionResponse
{
    public function __construct(
        private $vacations
    ) {
    }

    public function getVacations(): Collection
    {
        return $this->vacations;
    }
}
