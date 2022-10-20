<?php

declare(strict_types=1);

namespace App\Actions\Poll;

use App\Repository\PollRepository;

final class PollDeleteAction
{
    public function __construct(private PollRepository $pollRepository)
    {
    }

    public function execute(PollDeleteRequest $request): void
    {
        $this->pollRepository->delete($request->getPollId());
    }
}
