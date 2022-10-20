<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\VacationOrHospital;

use App\Http\Requests\Api\ApiFormRequest;

final class VacationOrHospitalCreateValidationRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'type' => 'required|boolean',
            'dateStart' => 'required|date|date_format:Y-m-d',
            'dateEnd' => 'required|date|date_format:Y-m-d|after:dateStart',
            'comment' => 'string|max:200|nullable',
        ];
    }
}
