<?php

declare(strict_types=1);

namespace App\Actions\Handbook;

use Illuminate\Database\Eloquent\Collection;

final class MaritalStatusCollectionResponse
{
    public function __construct(
        private $maritalStatus,
    ) {
    }

    public function getMaritalStatus(): Collection
    {
        return $this->maritalStatus;
    }
}
