<?php

declare(strict_types=1);

namespace App\Actions\Poll;

final class GetPollCollectionRequest
{
    public function __construct(
        private ?int $page,
        private ?string $sort,
        private ?string $direction,
    ) {
    }

    public function getPage(): ?int
    {
        return $this->page;
    }

    public function getSort(): ?string
    {
        return $this->sort;
    }

    public function getDirection(): ?string
    {
        return $this->direction;
    }
}
