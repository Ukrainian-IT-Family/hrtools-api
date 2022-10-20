<?php

declare(strict_types=1);

namespace App\Actions\PollResult;

use App\Models\Poll;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

final class PollResultResponse
{
    public function __construct(
        private object $pollResult
    ) {
    }

    public function getPollResultUser(): User
    {
        return $this->pollResult->user;
    }

    public function getPollResultUserId(): int
    {
        return $this->pollResult->user->id;
    }

    public function getPollResultId(): int
    {
        return $this->pollResult->id;
    }

    public function getPoll(): Poll
    {
        return $this->pollResult->poll;
    }

    public function getPollQuestion(): Collection
    {
        return $this->pollResult->poll->questions;
    }

    public function getPollResultAnswers(): Collection
    {
        return $this->pollResult->pollAnswers;
    }
}
