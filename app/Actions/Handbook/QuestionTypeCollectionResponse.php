<?php

declare(strict_types=1);

namespace App\Actions\Handbook;

use Illuminate\Database\Eloquent\Collection;

final class QuestionTypeCollectionResponse
{
    public function __construct(
        private $questionType,
    ) {
    }

    public function getQuestionType(): Collection
    {
        return $this->questionType;
    }
}
