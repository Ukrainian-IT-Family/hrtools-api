<?php

namespace App\Actions\Resume;

use App\Repository\ResumeRepository;
use Illuminate\Support\Facades\Storage;

final class DeleteUserResumeAction
{
    public function __construct(private ResumeRepository $repository)
    {
    }

    public function execute(DeleteUserResumeRequest $request): void
    {
        $userId = $request->getUserId();
        $resume = $this->repository->getByUserId($userId);

        if ($resume) {
            Storage::disk('s3')->delete($resume->path);
            $this->repository->delete($resume->getId());
        }
    }
}
