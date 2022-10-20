<?php

declare(strict_types=1);

namespace App\Actions\Poll;

use Illuminate\Database\Eloquent\Collection;

final class PollCollectionResponse
{
    public function __construct(
        private $polls
    ) {
    }

    public function getPolls(): Collection
    {
        return $this->polls;
    }
}
