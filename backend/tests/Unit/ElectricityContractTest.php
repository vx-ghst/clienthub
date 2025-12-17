<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

use App\Models\Contracts\Contract;
use App\Models\Contracts\ElectricityContract;
use App\Enums\Contracts\ContractType;
use App\Enums\Contracts\ContractStatus;
use App\Models\Client;

class ElectricityContractTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_creates_electricity_contract()
    {
        $client = Client::factory()->create();

        $contract = Contract::create([
            'client_id' => $client->id,
            'type' => ContractType::Electricity,
            'status' => ContractStatus::Active,
            'start_date' => now(),
        ]);

        $electricity = ElectricityContract::create([
            'contract_id' => $contract->id,
            'ean' => '541448860000123456',
            'power_kw' => 9.2,
            'tariff_code' => 'FIXED_2025',
            'tariff_price_kwh' => 0.3245,
            'tariff_subscription' => 12.50,
            'advance_amount' => 95.00,
            'advance_frequency' => 'monthly',
        ]);

        $this->assertDatabaseHas('electricity_contracts', [
            'contract_id' => $contract->id,
            'ean' => '541448860000123456',
        ]);
    }


    #[Test]
    public function contract_has_one_electricity_contract()
    {
        $contract = Contract::factory()->create([
            'type' => ContractType::Electricity,
        ]);

        $electricity = ElectricityContract::factory()->create([
            'contract_id' => $contract->id,
        ]);

        $this->assertNotNull($contract->electricity);
        $this->assertEquals($electricity->id, $contract->electricity->id);
    }
}
