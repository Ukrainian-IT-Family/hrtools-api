<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\User;

use App\Http\Requests\Api\ApiFormRequest;

final class UpdateUserValidationRequest extends ApiFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'firstName' => 'string|min:2',
            'middleName' => 'string',
            'lastName' => 'string|min:2',
            'birthday' => 'date|before:tomorrow|date_format:Y-m-d',
            'gender' => 'boolean',
            'maritalStatus' => 'integer',
            'children' => 'array',
            'children.*.fullName' => 'string',
            'children.*.gender' => 'boolean',
            'children.*.birthday' => 'date|before:tomorrow|date_format:Y-m-d',
            'region' => 'string',
            'area' => 'string',
            'town' => 'string',
            'postOffice' => 'string',
            'contactsPhones' => 'array|min:1',
            'contactsPhones.*.id' => 'integer',
            'contactsPhones.*.phone' => 'regex:/[0-9]{12}/',
            'email' => 'required|email|unique:users,email,'.$this->id,
            'linkedin' => 'string',
            'facebook' => 'string',
            'emergency' => 'array',
            'emergency.*.fullName' => 'string',
            'emergency.*.relationship' => 'integer',
            'emergency.*.emergencyPhones' => 'array|min:1',
            'emergency.*.emergencyPhones.*.phone' => 'required|regex:/[0-9]{12}/',
            'role' => 'integer',
            'workers' => 'array',
            'workers.*.id' => 'integer',
            'workTime' => 'string',
            'position' => 'string',
            'hireDate' => 'date|before:tomorrow|date_format:Y-m-d',
        ];
    }
}
