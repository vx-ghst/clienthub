<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Client;
use App\Models\Contracts\Contract;
use App\Models\Contracts\ElectricityContract;
use App\Enums\Contracts\ContractType;
use App\Enums\Contracts\ContractStatus;
use App\Services\ElectricityContractService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;

class ElectricityContractServiceTest extends TestCase
{
    use RefreshDatabase;

    private ElectricityContractService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ElectricityContractService();
    }

    public function test_create_electricity_contract_success(): void
    {
        $client = Client::factory()->create();
        $contract = Contract::factory()->create([
            'client_id' => $client->id,
            'type' => ContractType::Electricity->value,
            'status' => ContractStatus::Active->value,
            'start_date' => now(),
        ]);

        $data = [
            'contract_id' => $contract->id,
            'ean' => '123456789012345678',
            'power_kw' => 6.5,
            'tariff_code' => 'T1',
            'tariff_price_kwh' => 0.25,
            'tariff_subscription' => 5.0,
            'advance_amount' => 20,
            'advance_frequency' => 'monthly',
        ];

        $ec = $this->service->create($data);

        $this->assertDatabaseHas('electricity_contracts', [
            'id' => $ec->id,
            'contract_id' => $contract->id,
        ]);
    }

    public function test_create_invalid_contract_type_throws_validation(): void
    {
        $client = Client::factory()->create();
        $contract = Contract::factory()->create([
            'client_id' => $client->id,
            'type' => ContractType::Gas->value, // mauvais type
            'status' => ContractStatus::Active->value,
            'start_date' => now(),
        ]);

        $this->expectException(ValidationException::class);

        $this->service->create([
            'contract_id' => $contract->id,
            'ean' => '123456789012345678',
            'power_kw' => 6.5,
            'tariff_code' => 'T1',
            'tariff_price_kwh' => 0.25,
            'tariff_subscription' => 5.0,
            'advance_amount' => 20,
            'advance_frequency' => 'monthly',
        ]);
    }

    public function test_update_electricity_contract_success(): void
    {
        $client = Client::factory()->create();
        $contract = Contract::factory()->create([
            'client_id' => $client->id,
            'type' => ContractType::Electricity->value,
            'status' => ContractStatus::Active->value,
            'start_date' => now(),
        ]);

        $ec = ElectricityContract::factory()->create([
            'contract_id' => $contract->id,
            'ean' => '123456789012345678',
            'power_kw' => 6.5,
            'tariff_code' => 'T1',
            'tariff_price_kwh' => 0.25,
            'tariff_subscription' => 5.0,
            'advance_amount' => 20,
            'advance_frequency' => 'monthly',
        ]);

        $data = [
            'power_kw' => 7.0,
            'tariff_price_kwh' => 0.28,
        ];

        $updated = $this->service->update($ec, $data);

        $this->assertEquals(7.0, $updated->power_kw);
        $this->assertEquals(0.28, $updated->tariff_price_kwh);
    }
}
