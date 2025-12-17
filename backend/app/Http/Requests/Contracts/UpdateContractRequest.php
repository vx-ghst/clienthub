<?php

namespace App\Http\Requests\Contracts;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\Contracts\ContractType;
use App\Enums\Contracts\ContractStatus;

class UpdateContractRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'client_id' => ['sometimes', 'exists:clients,id'],

            'type' => [
                'sometimes',
                'string',
                Rule::in(ContractType::values()),
                Rule::unique('contracts', 'type')
                    ->where(fn($q) => $q->where('client_id', $this->input('client_id')))
                    ->ignore($this->route('contract')),
            ],

            'status' => ['sometimes', 'string', Rule::in(ContractStatus::values())],
            'start_date' => ['sometimes', 'date'],
            'end_date' => ['nullable', 'date'],
        ];
    }
}
