<?php

namespace App\Actions\Resume;

use App\Repository\ResumeRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

final class UploadProfileResumeAction
{
    public function __construct(private ResumeRepository $repository)
    {
    }

    public function execute(UploadProfileResumeRequest $request): UploadProfileResumeResponse
    {
        $user = Auth::user();
        $userId = $user->id;

        $resume = $this->repository->getByUserId($userId);
        if ($resume && $resume->name != $request->getResume()->getFilename()) {
            Storage::disk('s3')->delete($resume->path);
        }

        $resumePath = Storage::disk('s3')->putFileAs(
            Config::get('filesystems.resumes_dir'),
            $request->getResume(),
            $request->getResume()->hashName(),
            's3'
        );

        $resume = $this->repository->updateOrCreate(
            ['user_id' => $userId],
            [
                'name' => $request->getResume()->hashName(),
                'path' => Storage::disk('s3')->url($resumePath),
            ]
        );

        return new UploadProfileResumeResponse($resume);
    }
}
