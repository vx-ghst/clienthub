<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Client;
use App\Enums\ContractType;
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
    protected $model = \App\Models\Contract::class;

    public function definition()
    {
        return [
            'type' => $this->faker->randomElement(ContractType::values()),
            'status' => $this->faker->randomElement(['active', 'closed']),
            'start_date' => $this->faker->date(),
            'end_date' => null,
            'client_id' => Client::factory(),
        ];
    }
}
