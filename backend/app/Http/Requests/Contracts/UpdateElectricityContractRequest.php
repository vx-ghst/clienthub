<?php

namespace App\Http\Requests\Contracts;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateElectricityContractRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // ID du ElectricityContract courant (table electricity_contracts)
        $electricityContractId = $this->route('electricity_contract');

        return [
            'contract_id' => [
                'sometimes',
                'exists:contracts,id',
                Rule::unique('electricity_contracts', 'contract_id')
                    ->ignore($electricityContractId, 'id'),
            ],
            'ean' => [
                'sometimes',
                'digits:18',
                Rule::unique('electricity_contracts', 'ean')
                    ->ignore($electricityContractId, 'id'),
            ],
            'power_kw' => ['sometimes', 'numeric', 'min:0.1'],
            'tariff_code' => ['sometimes', 'string'],
            'tariff_price_kwh' => ['sometimes', 'numeric', 'min:0'],
            'tariff_subscription' => ['sometimes', 'numeric', 'min:0'],
            'advance_amount' => ['sometimes', 'numeric', 'min:0'],
            'advance_frequency' => ['sometimes', Rule::in(['monthly', 'quarterly'])],
        ];
    }

    public function messages(): array
    {
        return [
            'contract_id.exists' => 'The contract must exist.',
            'contract_id.unique' => 'This contract already has an electricity contract.',
            'ean.digits' => 'EAN must be exactly 18 digits.',
            'ean.unique' => 'This EAN is already used for another electricity contract.',
            'power_kw.min' => 'Power must be at least 0.1 kW.',
            'tariff_price_kwh.min' => 'Tariff price per kWh must be non-negative.',
            'tariff_subscription.min' => 'Tariff subscription must be non-negative.',
            'advance_amount.min' => 'Advance amount must be non-negative.',
            'advance_frequency.in' => 'Advance frequency must be either monthly or quarterly.',
        ];
    }
}
