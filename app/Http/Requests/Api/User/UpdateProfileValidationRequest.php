<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\User;

use App\Http\Requests\Api\ApiFormRequest;
use Illuminate\Support\Facades\Auth;

final class UpdateProfileValidationRequest extends ApiFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'firstName' => 'required|string|min:2',
            'middleName' => 'required|string|min:2',
            'lastName' => 'required|string|min:2',
            'birthday' => 'required|date|before:tomorrow|date_format:Y-m-d',
            'gender' => 'required|boolean',
            'maritalStatus' => 'required|integer',
            'children' => 'array',
            'children.*.fullName' => 'string',
            'children.*.gender' => 'boolean',
            'children.*.birthday' => 'date|before:tomorrow|date_format:Y-m-d',
            'region' => 'required|string',
            'area' => 'required|string',
            'town' => 'required|string',
            'postOffice' => 'required|string',
            'contactsPhones' => 'required|array|min:1',
            'contactsPhones.*.id' => 'integer',
            'contactsPhones.*.phone' => 'required|regex:/[0-9]{12}/',
            'email' => 'required|email|unique:users,email,'.Auth::user()->id,
            'linkedin' => 'string',
            'facebook' => 'string',
            'emergency' => 'required|array|min:1',
            'emergency.*.fullName' => 'required|string',
            'emergency.*.relationship' => 'required|integer',
            'emergency.*.emergencyPhones' => 'required|array|min:1',
            'emergency.*.emergencyPhones.*.phone' => 'required|regex:/[0-9]{12}/',

        ];
    }
}
