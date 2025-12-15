<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // autorise tous les utilisateurs pour l’instant
    }

    public function rules(): array
    {
        return [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname'  => ['required', 'string', 'max:255'],
            'email'     => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'regex:/^(?:\+32|0)\d{8,9}$/'],

        ];
    }

    public function messages(): array
    {
        return [
            'firstname.required' => 'First name is required.',
            'lastname.required'  => 'Last name is required.',
            'email.required'     => 'Email address is required.',
            'email.email'        => 'Email address must be a valid email.',
            'phone.regex'        => 'The phone number must be a valid Belgian number (+32 or 0 followed by 8-9 digits).',

        ];
    }
}
