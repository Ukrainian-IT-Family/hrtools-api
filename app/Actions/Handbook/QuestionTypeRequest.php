<?php

declare(strict_types=1);

namespace App\Actions\Handbook;

final class QuestionTypeRequest
{
    public function __construct(
        private int $questionTypeId,
    ) {
    }

    public function getQuestionTypeId(): int
    {
        return $this->questionTypeId;
    }
}
