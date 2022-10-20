<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\User;

use App\Http\Requests\Api\ApiFormRequest;
use Illuminate\Validation\Rules\File;

final class UploadProfileResumeValidationRequest extends ApiFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'resume' => [
                File::types(['doc', 'pdf', 'docx', 'zip'])->max(5 * 1024),
            ],
        ];
    }
}
