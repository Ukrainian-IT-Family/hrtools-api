<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\User;

use App\Http\Requests\Api\ApiFormRequest;

final class FindUsersValidationRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'fullName' => 'required|string|min:3',
        ];
    }
}
