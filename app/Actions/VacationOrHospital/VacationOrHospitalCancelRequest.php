<?php

declare(strict_types=1);

namespace App\Actions\VacationOrHospital;

final class VacationOrHospitalCancelRequest
{
    public function __construct(
        private int $id
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }
}
