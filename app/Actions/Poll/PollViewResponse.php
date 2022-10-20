<?php

declare(strict_types=1);

namespace App\Actions\Poll;

use Illuminate\Database\Eloquent\Collection;

final class PollViewResponse
{
    public function __construct(
        private object $poll,
        private object $pollResult
    ) {
    }

    public function getPoll(): object
    {
        return $this->poll;
    }

    public function getPollId(): int
    {
        return $this->poll->id;
    }

    public function getPollTitle(): string
    {
        return $this->poll->title;
    }

    public function getPollQuestions(): Collection
    {
        return $this->poll->questions;
    }

    public function getPollResult(): object
    {
        return $this->pollResult;
    }

    public function getPollResultId(): int
    {
        return $this->pollResult->id;
    }

    public function getPollResultAnswers(): Collection
    {
        return $this->pollResult->pollAnswers;
    }
}
