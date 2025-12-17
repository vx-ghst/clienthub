<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreElectricityContractRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'contract_id' => [
                'required',
                'exists:contracts,id',
                Rule::unique('electricity_contracts', 'contract_id'),
            ],

            'ean' => [
                'required',
                'digits:18',
                Rule::unique('electricity_contracts', 'ean'),
            ],

            'power_kw' => ['required', 'numeric', 'min:0.1'],

            'tariff_code' => ['required', 'string'],
            'tariff_price_kwh' => ['required', 'numeric', 'min:0'],
            'tariff_subscription' => ['required', 'numeric', 'min:0'],

            'advance_amount' => ['required', 'numeric', 'min:0'],
            'advance_frequency' => ['required', Rule::in(['monthly', 'quarterly'])],
        ];
    }
}
