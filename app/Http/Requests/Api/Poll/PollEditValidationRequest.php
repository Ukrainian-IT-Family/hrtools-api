<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\Poll;

use App\Http\Requests\Api\ApiFormRequest;

final class PollEditValidationRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'title' => 'required|string',
            'anonymous' => 'required|boolean',
            'questions' => 'required|array|min:1|max:15',
            'questions.*.id' => 'integer',
            'questions.*.name' => 'required|string',
            'questions.*.required' => 'required|boolean',
            'questions.*.type' => 'required|integer',
            'questions.*.answers' => 'required|array|max:6',
            'questions.*.answers.*.id' => 'integer',
            'questions.*.answers.*.value' => 'string|nullable',
        ];
    }
}
