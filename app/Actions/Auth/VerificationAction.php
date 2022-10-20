<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Exceptions\ExpiredUrlProvidedException;
use App\Exceptions\InvalidUrlProvidedException;
use App\Repository\UserRepository;

final class VerificationAction
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function execute(VerificationRequest $request): void
    {
        if ($request->getValidSignature()->get('expires') < now()->timestamp) {
            throw new ExpiredUrlProvidedException();
        }

        if (! $request->getValidSignature()->hasValidSignature()) {
            throw new InvalidUrlProvidedException();
        }

        $user = $this->userRepository->getById($request->getUserId());

        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }
    }
}
