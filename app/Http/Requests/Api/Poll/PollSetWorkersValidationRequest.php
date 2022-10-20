<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\Poll;

use App\Http\Requests\Api\ApiFormRequest;

final class PollSetWorkersValidationRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'workers' => 'array',
            'workers.*.id' => 'integer',
        ];
    }
}
