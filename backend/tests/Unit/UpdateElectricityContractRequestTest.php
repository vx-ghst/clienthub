<?php

namespace Tests\Unit\Requests\Contracts;

use Tests\TestCase;
use App\Models\Contracts\ElectricityContract;
use App\Models\Contracts\Contract;
use App\Http\Requests\Contracts\UpdateElectricityContractRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class UpdateElectricityContractRequestTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Simule un FormRequest avec la route renvoyant l'ID du ElectricityContract
     */
    private function getRequestWithElectricityContractId(int $id)
    {
        return new class($id) extends UpdateElectricityContractRequest {
            private int $cid;

            public function __construct(int $cid)
            {
                $this->cid = $cid;
            }

            public function route($param = null, $default = null)
            {
                if ($param === 'electricity_contract') {
                    return $this->cid;
                }
                return $default;
            }
        };
    }

    #[Test]
    public function partial_update_passes()
    {
        $contract = Contract::factory()->create(['type' => 'electricity']);
        $electricity = ElectricityContract::factory()->create(['contract_id' => $contract->id]);

        $data = ['power_kw' => 5.5];

        $request = $this->getRequestWithElectricityContractId($electricity->id);
        $validator = Validator::make($data, $request->rules());

        $this->assertTrue($validator->passes(), 'Validation should pass for partial update');
    }

    #[Test]
    public function invalid_ean_fails()
    {
        $contract = Contract::factory()->create(['type' => 'electricity']);
        $electricity = ElectricityContract::factory()->create(['contract_id' => $contract->id]);

        $data = ['ean' => '123']; // trop court, doit être 18 digits

        $request = $this->getRequestWithElectricityContractId($electricity->id);
        $validator = Validator::make($data, $request->rules());

        $this->assertTrue($validator->fails(), 'Validation should fail for invalid EAN');
        $this->assertArrayHasKey('ean', $validator->errors()->messages());
    }

    #[Test]
    public function invalid_advance_frequency_fails()
    {
        $contract = Contract::factory()->create(['type' => 'electricity']);
        $electricity = ElectricityContract::factory()->create(['contract_id' => $contract->id]);

        $data = ['advance_frequency' => 'weekly']; // seulement monthly ou quarterly autorisés

        $request = $this->getRequestWithElectricityContractId($electricity->id);
        $validator = Validator::make($data, $request->rules());

        $this->assertTrue($validator->fails(), 'Validation should fail for invalid advance frequency');
        $this->assertArrayHasKey('advance_frequency', $validator->errors()->messages());
    }

    #[Test]
    public function all_fields_valid_passes()
    {
        $contract = Contract::factory()->create(['type' => 'electricity']);
        $electricity = ElectricityContract::factory()->create(['contract_id' => $contract->id]);

        $data = [
            'contract_id' => $contract->id,
            'ean' => '123456789012345678',
            'power_kw' => 6.5,
            'tariff_code' => 'T1',
            'tariff_price_kwh' => 0.25,
            'tariff_subscription' => 5,
            'advance_amount' => 20,
            'advance_frequency' => 'monthly',
        ];

        $request = $this->getRequestWithElectricityContractId($electricity->id);
        $validator = Validator::make($data, $request->rules());

        $this->assertTrue($validator->passes(), 'Validation should pass when all fields are valid');
    }
}
