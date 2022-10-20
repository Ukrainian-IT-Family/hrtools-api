<?php

declare(strict_types=1);

namespace App\Actions\Handbook;

use App\Repository\QuestionTypeRepository;

final class QuestionTypeCollectionAction
{
    public function __construct(private QuestionTypeRepository $repository)
    {
    }

    public function execute(): QuestionTypeCollectionResponse
    {
        $questionTypes = $this->repository->getAll();

        return new QuestionTypeCollectionResponse($questionTypes);
    }
}
