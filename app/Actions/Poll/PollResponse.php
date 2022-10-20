<?php

declare(strict_types=1);

namespace App\Actions\Poll;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

final class PollResponse
{
    public function __construct(
        private object $poll
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

    public function getPollStatus(): int
    {
        return $this->poll->status;
    }

    public function getPollAnonymous(): bool
    {
        return (bool) $this->poll->anonymous;
    }

    public function getPollAuthor(): User
    {
        return $this->poll->author;
    }

    public function getPollAuthorId(): int
    {
        return $this->poll->created_by;
    }

    public function getPollDate(): string
    {
        return $this->poll->created_at;
    }

    public function getPollQuestions(): Collection
    {
        return $this->poll->questions;
    }

    public function getPollWorkersCount(): ?int
    {
        return $this->poll->workers_count;
    }

    public function getPollWorkers(): Collection
    {
        return $this->poll->workers;
    }

    public function getPollResults(): Collection
    {
        return $this->poll->pollResult;
    }

    public function getPollResultCount(): ?int
    {
        return $this->poll->poll_result_count;
    }

    public function isPassedByWorker(): bool
    {
        return $this->poll->passed;
    }
}
