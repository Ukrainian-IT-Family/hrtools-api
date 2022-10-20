<?php

declare(strict_types=1);

namespace App\Actions\User;

final class FindUserCollectionByFullNameRequest
{
    public function __construct(
        private string $fullName,
    ) {
    }

    public function getFullName(): string
    {
        return $this->fullName;
    }
}
