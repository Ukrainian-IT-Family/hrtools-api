<?php

declare(strict_types=1);

namespace App\Actions\Poll;

final class PollDeleteRequest
{
    public function __construct(
        private int $pollId
    ) {
    }

    public function getPollId(): int
    {
        return $this->pollId;
    }
}
