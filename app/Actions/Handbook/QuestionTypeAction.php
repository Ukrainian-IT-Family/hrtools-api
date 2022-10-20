<?php

declare(strict_types=1);

namespace App\Actions\Handbook;

use App\Repository\QuestionTypeRepository;

final class QuestionTypeAction
{
    public function __construct(private QuestionTypeRepository $repository)
    {
    }

    public function execute(QuestionTypeRequest $request): QuestionTypeResponse
    {
        $typeId = $request->getQuestionTypeId();
        $type = $this->repository->getById($typeId);

        return new QuestionTypeResponse($type);
    }
}
