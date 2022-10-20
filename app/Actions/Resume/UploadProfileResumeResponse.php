<?php

namespace App\Actions\Resume;

use App\Models\Resume;

final class UploadProfileResumeResponse
{
    public function __construct(private Resume $resume)
    {
    }

    public function getResume(): Resume
    {
        return $this->resume;
    }
}
