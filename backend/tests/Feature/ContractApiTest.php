<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Client;
use App\Models\Contract;
use App\Enums\ContractType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class ContractApiTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_creates_contract_via_api()
    {
        $client = Client::factory()->create();

        $response = $this->postJson('/api/contracts', [
            'client_id' => $client->id,
            'type' => ContractType::Mobile->value,
            'status' => 'active',
            'start_date' => now()->toDateString(),
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'client_id' => $client->id,
                'type' => ContractType::Mobile->value,
            ]);
    }

    #[Test]
    public function it_fails_for_duplicate_contract_type_via_api()
    {
        $client = Client::factory()->create();

        Contract::create([
            'client_id' => $client->id,
            'type' => ContractType::Electricity,
            'status' => 'active',
            'start_date' => now(),
        ]);

        $response = $this->postJson('/api/contracts', [
            'client_id' => $client->id,
            'type' => ContractType::Electricity->value,
            'status' => 'active',
            'start_date' => now()->toDateString(),
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('type');
    }
}
