<?php

declare(strict_types=1);

namespace App\Actions\Poll;

final class PollEditRequest
{
    public function __construct(
        private int $id,
        private string $title,
        private bool $anonymous,
        private array $questions
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function isAnonymous(): bool
    {
        return $this->anonymous;
    }

    public function getQuestions(): array
    {
        return $this->questions;
    }
}
