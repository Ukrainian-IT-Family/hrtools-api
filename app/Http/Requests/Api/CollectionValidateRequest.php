<?php

declare(strict_types=1);

namespace App\Http\Requests\Api;

use Illuminate\Validation\Rule;

final class CollectionValidateRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'page' => 'integer|min:1',
            'sort' => 'string',
            'direction' => [
                'string',
                Rule::in(['asc', 'desc']),
            ],
        ];
    }
}
