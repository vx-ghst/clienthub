<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use \Illuminate\Validation\ValidationException;
use App\Enums\ContractType;
use App\Models\Contract;

class StoreContractRequest extends FormRequest
{
    /**
     * Authorize all users for now before authentication logic is implemented.
     */
    public function authorize(): bool
    {
        return true; // allow all users for now before auth logic
    }

    /**
     * Validation rules for creating/updating a contract.
     */
    public function rules(): array
    {
        return [
            'type' => [
                'required',
                'string',
                Rule::in(ContractType::values()),
                function ($attribute, $value, $fail) {
                    $clientId = $this->input('client_id');
                    if ($clientId) {
                        try {
                            Contract::validateUniqueTypeForClient($clientId, $value);
                        } catch (ValidationException $e) {
                            $fail($e->errors()['type'][0]);
                        }
                    }
                }
            ],
            'status' => ['required', 'string', Rule::in(['active', 'closed'])],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date'],
            'client_id' => ['required', 'exists:clients,id'],
        ];
    }

    /**
     * Custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'type.required' => 'Contract type is required.',
            'type.in' => 'Contract type must be one of: ' . implode(', ', ContractType::values()),
            'status.required' => 'Status is required.',
            'status.in' => 'Status must be active or closed.',
            'start_date.required' => 'Start date is required.',
            'start_date.date' => 'Start date must be a valid date.',
            'end_date.date' => 'End date must be a valid date.',
            'client_id.required' => 'Client ID is required.',
            'client_id.exists' => 'Client does not exist.',
        ];
    }
}
