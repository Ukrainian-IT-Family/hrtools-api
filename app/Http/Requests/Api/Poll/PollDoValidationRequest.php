<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\Poll;

use App\Http\Requests\Api\ApiFormRequest;

final class PollDoValidationRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'answers' => 'required|array|min:1',
            'answers.*.id' => 'int',
            'answers.*.value' => 'string|nullable',
            'answers.*.questionId' => 'int',
        ];
    }
}
