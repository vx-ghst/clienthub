<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Client;
use App\Models\Contracts\Contract;
use App\Enums\Contracts\ContractType;
use App\Enums\Contracts\ContractStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ContractControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_returns_201_for_valid_data()
    {
        $client = Client::factory()->create();

        $payload = [
            'client_id' => $client->id,
            'type' => ContractType::Electricity->value,
            'status' => ContractStatus::Active->value,
            'start_date' => now()->toDateString(),
        ];

        $response = $this->postJson('/api/contracts', $payload);

        $response->assertStatus(201);
        $this->assertDatabaseHas('contracts', [
            'client_id' => $client->id,
            'type' => ContractType::Electricity->value,
        ]);
    }

    public function test_store_returns_422_for_duplicate_type_per_client()
    {
        $client = Client::factory()->create();

        Contract::create([
            'client_id' => $client->id,
            'type' => ContractType::Gas->value,
            'status' => ContractStatus::Active->value,
            'start_date' => now(),
        ]);

        $payload = [
            'client_id' => $client->id,
            'type' => ContractType::Gas->value,
            'status' => ContractStatus::Active->value,
            'start_date' => now()->toDateString(),
        ];

        $response = $this->postJson('/api/contracts', $payload);
        $response->assertStatus(422);
    }

    public function test_show_returns_404_for_missing_contract()
    {
        $response = $this->getJson('/api/contracts/999');
        $response->assertStatus(404);
    }

    public function test_update_contract()
    {
        $client = Client::factory()->create();

        $contract = Contract::create([
            'client_id' => $client->id,
            'type' => ContractType::Mobile->value,
            'status' => ContractStatus::Active->value,
            'start_date' => now(),
        ]);

        $payload = ['status' => ContractStatus::Closed->value];

        $response = $this->putJson("/api/contracts/{$contract->id}", $payload);

        $response->assertStatus(200);
        $this->assertEquals(ContractStatus::Closed->value, $contract->fresh()->status->value);
    }

    public function test_destroy_contract()
    {
        $client = Client::factory()->create();

        $contract = Contract::create([
            'client_id' => $client->id,
            'type' => ContractType::Electricity->value,
            'status' => ContractStatus::Active->value,
            'start_date' => now(),
        ]);

        $response = $this->deleteJson("/api/contracts/{$contract->id}");
        $response->assertStatus(204);
        $this->assertDatabaseMissing('contracts', ['id' => $contract->id]);
    }
}
