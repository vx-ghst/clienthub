<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\ContractType;
use App\Enums\ContractStatus;

class StoreContractRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $clientId = $this->input('client_id');

        return [
            'client_id' => ['required', 'exists:clients,id'],

            'type' => [
                'required',
                'string',
                Rule::in(ContractType::values()),
                Rule::unique('contracts', 'type')
                    ->where(fn($q) => $q->where('client_id', $clientId)),
            ],

            'status' => ['required', 'string', Rule::in(ContractStatus::values())],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'type.required' => 'Contract type is required.',
            'type.in' => 'Contract type must be one of: ' . implode(', ', ContractType::values()),
            'type.unique' => 'Client already has a contract of this type.',
            'status.required' => 'Contract status is required.',
            'status.in' => 'Status must be one of: ' . implode(', ', ContractStatus::values()),
            'start_date.required' => 'Start date is required.',
            'start_date.date' => 'Start date must be a valid date.',
            'end_date.date' => 'End date must be a valid date.',
            'client_id.required' => 'Client ID is required.',
            'client_id.exists' => 'Client does not exist.',
        ];
    }
}
