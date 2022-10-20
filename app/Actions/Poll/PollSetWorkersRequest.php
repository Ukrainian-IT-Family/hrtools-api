<?php

declare(strict_types=1);

namespace App\Actions\Poll;

final class PollSetWorkersRequest
{
    public function __construct(
        private int $pollId,
        private array $workers
    ) {
    }

    public function getPollId(): int
    {
        return $this->pollId;
    }

    public function getWorkers(): array
    {
        return $this->workers;
    }
}
