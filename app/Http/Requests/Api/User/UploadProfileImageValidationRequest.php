<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\User;

use App\Http\Requests\Api\ApiFormRequest;

final class UploadProfileImageValidationRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'image' => 'required|mimes:jpeg,png|max:5120',
        ];
    }
}
