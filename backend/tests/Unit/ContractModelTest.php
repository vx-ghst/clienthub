<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Contract;
use App\Models\Client;
use App\Enums\ContractType;
use App\Enums\ContractStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ContractModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_can_cast_type_and_status_to_enum()
    {
        $client = Client::factory()->create();

        $contract = Contract::create([
            'client_id' => $client->id,
            'type' => ContractType::Gas->value,
            'status' => ContractStatus::Active->value,
            'start_date' => now(),
        ]);

        $this->assertInstanceOf(ContractType::class, $contract->type);
        $this->assertInstanceOf(ContractStatus::class, $contract->status);
        $this->assertEquals(ContractType::Gas, $contract->type);
        $this->assertEquals(ContractStatus::Active, $contract->status);
    }

    public function test_it_belongs_to_client()
    {
        $client = Client::factory()->create();

        $contract = Contract::create([
            'client_id' => $client->id,
            'type' => ContractType::Electricity->value,
            'status' => ContractStatus::Active->value,
            'start_date' => now(),
        ]);

        $this->assertInstanceOf(Client::class, $contract->client);
        $this->assertEquals($client->id, $contract->client->id);
    }
}
