<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Exceptions\FailedSentPasswordResetLinkException;
use Illuminate\Support\Facades\Password;

final class ForgotPasswordAction
{
    public function execute(ForgotPasswordRequest $request): void
    {
        $response = Password::broker()->sendResetLink(['email' => $request->getEmail()]);

        if ($response != Password::RESET_LINK_SENT) {
            throw new FailedSentPasswordResetLinkException();
        }
    }
}
