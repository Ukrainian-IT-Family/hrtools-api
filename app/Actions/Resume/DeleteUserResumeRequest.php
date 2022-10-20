<?php

namespace App\Actions\Resume;

final class DeleteUserResumeRequest
{
    public function __construct(private int $userId)
    {
    }

    public function getUserId(): int
    {
        return $this->userId;
    }
}
