<?php

declare(strict_types=1);

namespace App\Actions\VacationOrHospital;

final class VacationOrHospitalCreateRequest
{
    public function __construct(
        private bool $type,
        private string $dateStart,
        private string $dateEnd,
        private ?string $comment
    ) {
    }

    public function isType(): bool
    {
        return $this->type;
    }

    public function getDateStart(): string
    {
        return $this->dateStart;
    }

    public function getDateEnd(): string
    {
        return $this->dateEnd;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }
}
