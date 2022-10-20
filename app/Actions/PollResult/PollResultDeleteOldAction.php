<?php

declare(strict_types=1);

namespace App\Actions\PollResult;

use App\Repository\PollResultRepository;

final class PollResultDeleteOldAction
{
    public function __construct(
        private PollResultRepository $resultRepository
    ) {
    }

    public function execute(int $pollId, array $workers): void
    {
        $pollResults = $this->resultRepository->getPollResultsWithOldWorkers($pollId, $workers);

        foreach ($pollResults as $result) {
            $this->resultRepository->delete($result->id);
        }
    }
}
