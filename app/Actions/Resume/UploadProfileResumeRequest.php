<?php

namespace App\Actions\Resume;

use Illuminate\Http\UploadedFile;

final class UploadProfileResumeRequest
{
    public function __construct(private UploadedFile $resume)
    {
    }

    public function getResume(): UploadedFile
    {
        return $this->resume;
    }
}
