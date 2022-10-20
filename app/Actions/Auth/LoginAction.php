<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use Illuminate\Support\Facades\Auth;

final class LoginAction
{
    public function execute(LoginRequest $request): AuthenticationResponse
    {
        $token = Auth::attempt([
            'email' => $request->getEmail(),
            'password' => $request->getPassword(),
        ]);

        if (! $token) {
            return new AuthenticationResponse((object) [], '', '', 0);
        }

        $user = Auth::user();

        return new AuthenticationResponse(
            $user,
            $token,
            'bearer',
            auth()->factory()->getTTL() * 60
        );
    }
}
