<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Exceptions\InvalidResetPasswordTokenException;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

final class ResetPasswordAction
{
    public function execute(ResetPasswordRequest $request): void
    {
        $response = Password::broker()->reset([
            'email' => $request->getEmail(),
            'password' => $request->getPassword(),
            'password_confirmation' => $request->getPasswordConfirmation(),
            'token' => $request->getToken(),
        ], function ($user, $password) {
            $user->forceFill(['password' => Hash::make($password)])->save();
            event(new PasswordReset($user));
        });

        if ($response == Password::INVALID_TOKEN) {
            throw new InvalidResetPasswordTokenException();
        }

        if ($response != Password::PASSWORD_RESET) {
            throw new InvalidResetPasswordTokenException();
        }
    }
}
