<?php

declare(strict_types=1);

namespace App\Actions\PollResult;

final class PollResultCreateRequest
{
    public function __construct(
        private int $pollId,
        private array $answers
    ) {
    }

    public function getPollId(): int
    {
        return $this->pollId;
    }

    public function getAnswers(): array
    {
        return $this->answers;
    }
}
