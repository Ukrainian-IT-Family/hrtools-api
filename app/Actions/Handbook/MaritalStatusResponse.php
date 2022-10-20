<?php

declare(strict_types=1);

namespace App\Actions\Handbook;

final class MaritalStatusResponse
{
    public function __construct(
        private object $status,
    ) {
    }

    public function getMaritalStatusName(): string
    {
        return $this->status->name;
    }

    public function getMaritalStatusId(): int
    {
        return $this->status->id;
    }
}
