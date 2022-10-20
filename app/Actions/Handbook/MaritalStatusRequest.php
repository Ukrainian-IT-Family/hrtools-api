<?php

declare(strict_types=1);

namespace App\Actions\Handbook;

final class MaritalStatusRequest
{
    public function __construct(
        private int $maritalStatusId,
    ) {
    }

    public function getMaritalStatusId(): int
    {
        return $this->maritalStatusId;
    }
}
