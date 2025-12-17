<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Client;
use App\Models\Contracts\Contract;
use App\Models\Contracts\ElectricityContract;
use App\Enums\Contracts\ContractType;
use App\Enums\Contracts\ContractStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ElectricityContractControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_electricity_contract(): void
    {
        $client = Client::factory()->create();
        $contract = Contract::factory()->create([
            'client_id' => $client->id,
            'type' => ContractType::Electricity->value,
            'status' => ContractStatus::Active->value,
            'start_date' => now(),
        ]);

        $payload = [
            'contract_id' => $contract->id,
            'ean' => '123456789012345678',
            'power_kw' => 6.5,
            'tariff_code' => 'T1',
            'tariff_price_kwh' => 0.25,
            'tariff_subscription' => 5.0,
            'advance_amount' => 20,
            'advance_frequency' => 'monthly',
        ];

        $response = $this->postJson('/api/electricity-contracts', $payload);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'contract_id' => $contract->id,
                'power_kw' => '6.50',
            ]);
    }

    public function test_update_electricity_contract(): void
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

        $updateData = [
            'power_kw' => 7.0,
            'tariff_price_kwh' => 0.28,
        ];

        $response = $this->putJson("/api/electricity-contracts/{$ec->id}", $updateData);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $ec->id,
                'power_kw' => '7.00',
                'tariff_price_kwh' => '0.2800',
            ]);

    }
}
