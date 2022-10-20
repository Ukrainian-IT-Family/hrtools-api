<?php

namespace App\Actions\Resume;

use Illuminate\Http\UploadedFile;

final class UploadUserResumeRequest
{
    public function __construct(private int $userId, private UploadedFile $resume)
    {
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getResume(): UploadedFile
    {
        return $this->resume;
    }
}
