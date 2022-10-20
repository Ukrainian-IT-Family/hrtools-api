<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Exceptions\EmailAlreadyVerifiedException;
use App\Repository\UserRepository;

final class ResendVerificationAction
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function execute(VerificationRequest $request): void
    {
        if (! auth()->user() && $request->getUserId()) {
            $user = $this->userRepository->getById($request->getUserId());
            auth()->login($user);
        }

        if (auth()->user()->hasVerifiedEmail()) {
            throw new EmailAlreadyVerifiedException();
        }

        auth()->user()->sendEmailVerificationNotification();
    }
}
