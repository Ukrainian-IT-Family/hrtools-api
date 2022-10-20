<?php

namespace App\Actions\Resume;

use App\Repository\ResumeRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

final class DeleteProfileResumeAction
{
    public function __construct(private ResumeRepository $repository)
    {
    }

    public function execute(): void
    {
        $userId = Auth::id();
        $resume = $this->repository->getByUserId($userId);

        if ($resume) {
            Storage::disk('s3')->delete($resume->path);
            $this->repository->delete($resume->getId());
        }
    }
}
