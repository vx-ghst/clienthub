<?php

namespace Database\Factories\Contracts;

use App\Enums\Contracts\ContractStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Client;
use App\Enums\COntracts\ContractType;
use App\Models\Contracts\Contract;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contract>
 */
class ContractFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Contract::class;

    public function definition()
    {
        return [
            'type' => $this->faker->randomElement(ContractType::values()),
            'status' => $this->faker->randomElement(ContractStatus::values()),
            'start_date' => $this->faker->date(),
            'end_date' => null,
            'client_id' => Client::factory(),
        ];
    }
}
