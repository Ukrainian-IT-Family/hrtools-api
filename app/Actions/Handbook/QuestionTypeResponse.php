<?php

declare(strict_types=1);

namespace App\Actions\Handbook;

final class QuestionTypeResponse
{
    public function __construct(
        private object $questionType,
    ) {
    }

    public function getQuestionTypeName(): string
    {
        return $this->questionType->name;
    }

    public function getQuestionTypeId(): int
    {
        return $this->questionType->id;
    }
}
