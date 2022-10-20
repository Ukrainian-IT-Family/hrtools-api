<?php

declare(strict_types=1);

namespace App\Actions\PollResult;

use Illuminate\Database\Eloquent\Collection;

final class PollResultCollectionResponse
{
    public function __construct(
        private $pollResults
    ) {
    }

    public function getPollResults(): Collection
    {
        return $this->pollResults;
    }
}
