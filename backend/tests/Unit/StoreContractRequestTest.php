<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Client;
use App\Enums\ContractType;
use App\Enums\ContractStatus;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StoreContractRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_validation_passes_with_valid_data()
    {
        $client = Client::factory()->create();

        $data = [
            'client_id' => $client->id,
            'type' => ContractType::Electricity->value,
            'status' => ContractStatus::Active->value,
            'start_date' => now()->toDateString(),
        ];

        $validator = Validator::make($data, [
            'client_id' => ['required', 'exists:clients,id'],
            'type' => ['required', 'string', Rule::in(ContractType::values())],
            'status' => ['required', 'string', Rule::in(ContractStatus::values())],
            'start_date' => ['required', 'date'],
        ]);

        $this->assertTrue($validator->passes());
    }

    public function test_validation_fails_for_invalid_type()
    {
        $client = Client::factory()->create();

        $data = [
            'client_id' => $client->id,
            'type' => 'invalid_type',
            'status' => ContractStatus::Active->value,
            'start_date' => now()->toDateString(),
        ];

        $validator = Validator::make($data, [
            'client_id' => ['required', 'exists:clients,id'],
            'type' => ['required', 'string', Rule::in(ContractType::values())],
            'status' => ['required', 'string', Rule::in(ContractStatus::values())],
            'start_date' => ['required', 'date'],
        ]);

        $this->assertFalse($validator->passes());
    }

    public function test_validation_fails_for_duplicate_type_per_client()
    {
        $client = Client::factory()->create();

        \App\Models\Contract::create([
            'client_id' => $client->id,
            'type' => ContractType::Gas->value,
            'status' => ContractStatus::Active->value,
            'start_date' => now(),
        ]);

        $data = [
            'client_id' => $client->id,
            'type' => ContractType::Gas->value,
            'status' => ContractStatus::Active->value,
            'start_date' => now()->toDateString(),
        ];

        $validator = Validator::make($data, [
            'client_id' => ['required', 'exists:clients,id'],
            'type' => [
                'required', 'string', Rule::in(ContractType::values()),
                Rule::unique('contracts')->where(fn($q) => $q->where('client_id', $client->id))
            ],
            'status' => ['required', 'string', Rule::in(ContractStatus::values())],
            'start_date' => ['required', 'date'],
        ]);

        $this->assertFalse($validator->passes());
    }
}
