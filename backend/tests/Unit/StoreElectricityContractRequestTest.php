<?php

namespace Tests\Unit\Requests\Contracts;

use Tests\TestCase;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Contracts\StoreElectricityContractRequest;
use App\Models\Contracts\Contract;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StoreElectricityContractRequestTest extends TestCase
{
    use RefreshDatabase;

    protected function rules(array $data): array
    {
        $request = new StoreElectricityContractRequest();
        return $request->rules();
    }

    public function test_valid_data_passes()
    {
        $contract = Contract::factory()->create(['type' => 'electricity']);
        $data = [
            'contract_id' => $contract->id,
            'ean' => '123456789012345678',
            'power_kw' => 6.5,
            'tariff_code' => 'T1',
            'tariff_price_kwh' => 0.25,
            'tariff_subscription' => 5.0,
            'advance_amount' => 20.0,
            'advance_frequency' => 'monthly',
        ];

        $validator = Validator::make($data, $this->rules($data));
        $this->assertTrue($validator->passes());
    }

    public function test_contract_id_must_exist_and_be_electricity()
    {
        $data = ['contract_id' => 999, 'ean' => '123456789012345678', 'power_kw' => 6.5, 'tariff_code' => 'T1', 'tariff_price_kwh' => 0.25, 'tariff_subscription' => 5.0, 'advance_amount' => 20.0, 'advance_frequency' => 'monthly'];

        $validator = Validator::make($data, $this->rules($data));
        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('contract_id', $validator->errors()->messages());
    }

    public function test_ean_must_be_18_digits()
    {
        $contract = Contract::factory()->create(['type' => 'electricity']);
        $data = [
            'contract_id' => $contract->id,
            'ean' => '123', // trop court
            'power_kw' => 6.5,
            'tariff_code' => 'T1',
            'tariff_price_kwh' => 0.25,
            'tariff_subscription' => 5.0,
            'advance_amount' => 20.0,
            'advance_frequency' => 'monthly',
        ];

        $validator = Validator::make($data, $this->rules($data));
        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('ean', $validator->errors()->messages());
    }

    public function test_power_kw_must_be_positive()
    {
        $contract = Contract::factory()->create(['type' => 'electricity']);
        $data = [
            'contract_id' => $contract->id,
            'ean' => '123456789012345678',
            'power_kw' => 0,
            'tariff_code' => 'T1',
            'tariff_price_kwh' => 0.25,
            'tariff_subscription' => 5.0,
            'advance_amount' => 20.0,
            'advance_frequency' => 'monthly',
        ];

        $validator = Validator::make($data, $this->rules($data));
        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('power_kw', $validator->errors()->messages());
    }

    public function test_advance_frequency_must_be_valid()
    {
        $contract = Contract::factory()->create(['type' => 'electricity']);
        $data = [
            'contract_id' => $contract->id,
            'ean' => '123456789012345678',
            'power_kw' => 6.5,
            'tariff_code' => 'T1',
            'tariff_price_kwh' => 0.25,
            'tariff_subscription' => 5.0,
            'advance_amount' => 20.0,
            'advance_frequency' => 'weekly', // invalide
        ];

        $validator = Validator::make($data, $this->rules($data));
        $this->assertFalse($validator->passes());
        $this->assertArrayHasKey('advance_frequency', $validator->errors()->messages());
    }
}
