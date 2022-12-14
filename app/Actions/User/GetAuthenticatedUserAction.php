<?php

declare(strict_types=1);

namespace App\Actions\User;

use Illuminate\Support\Facades\Auth;

final class GetAuthenticatedUserAction
{
    public function execute(): GetAuthenticatedUserResponse
    {
        return new GetAuthenticatedUserResponse(Auth::user());
    }
}
