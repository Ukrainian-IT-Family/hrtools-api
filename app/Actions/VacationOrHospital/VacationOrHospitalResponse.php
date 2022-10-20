<?php

declare(strict_types=1);

namespace App\Actions\VacationOrHospital;

use App\Models\User;

final class VacationOrHospitalResponse
{
    public function __construct(
        private object $VacationOrHospital
    ) {
    }

    public function getVacationOrHospitalUser(): User
    {
        return $this->VacationOrHospital->author;
    }

    public function getVacationOrHospitalUserId(): int
    {
        return $this->VacationOrHospital->author->id;
    }

    public function getVacationOrHospitalId(): int
    {
        return $this->VacationOrHospital->id;
    }

    public function getVacationOrHospitalType(): int
    {
        return (int) $this->VacationOrHospital->type;
    }

    public function getVacationOrHospitalDateStart(): string
    {
        return $this->VacationOrHospital->date_start;
    }

    public function getVacationOrHospitalDateEnd(): string
    {
        return $this->VacationOrHospital->date_end;
    }

    public function getVacationOrHospitalDaysCount(): int
    {
        return $this->VacationOrHospital->days_count;
    }

    public function getVacationOrHospitalStatus(): ?int
    {
        return $this->VacationOrHospital->status;
    }

    public function getVacationOrHospitalComment(): ?string
    {
        return $this->VacationOrHospital->comment;
    }

    public function getVacationOrHospitalDateCreate(): ?string
    {
        return $this->VacationOrHospital->created_at;
    }

    public function canDelete(): bool
    {
        return $this->VacationOrHospital->can_delete;
    }
}
