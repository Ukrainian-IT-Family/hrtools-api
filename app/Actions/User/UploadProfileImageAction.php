<?php

declare(strict_types=1);

namespace App\Actions\User;

use App\Repository\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

final class UploadProfileImageAction
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function execute(UploadProfileImageRequest $request): UploadProfileImageResponse
    {
        $user = Auth::user();

        $filePath = Storage::disk('s3')->putFileAs(
            Config::get('filesystems.profile_images_dir'),
            $request->getImage(),
            $request->getImage()->hashName(),
            's3'
        );

        $user->profile_image = Storage::disk('s3')->url($filePath);

        $user = $this->userRepository->save($user);

        return new UploadProfileImageResponse($user);
    }
}
