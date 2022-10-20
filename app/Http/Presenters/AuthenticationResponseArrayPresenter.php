<?php

declare(strict_types=1);

namespace App\Http\Presenters;

use App\Actions\Auth\AuthenticationResponse;

final class AuthenticationResponseArrayPresenter
{
    public function __construct(
        private UserArrayPresenter $userArrayPresenter
    ) {
    }

    public function present(AuthenticationResponse $response): array
    {
        return [
            'user' => $this->userArrayPresenter->present($response->getUser()),
            'access_token' => $response->getAccessToken(),
            'token_type' => $response->getTokenType(),
            'expires_in' => $response->getExpiresIn(),
        ];
    }
}
