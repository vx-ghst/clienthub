<?php

namespace Database\Factories\Contracts;

use App\Models\Contracts\ElectricityContract;
use App\Models\Contracts\Contract;
use Illuminate\Database\Eloquent\Factories\Factory;

class ElectricityContractFactory extends Factory
{
    protected $model = ElectricityContract::class;

    public function definition()
    {
        return [
            'contract_id' => Contract::factory()->state(function () {
                return [
                    'type' => 'electricity',
                    'status' => 'active',
                    'start_date' => now(),
                ];
            }),
            'ean' => $this->faker->numerify('5410##########'), // Code EAN belge standard
            'power_kw' => $this->faker->randomFloat(2, 3, 15), // puissance souscrite
            'tariff_code' => $this->faker->randomElement(['BASIC', 'PREMIUM', 'GREEN']),
            'tariff_price_kwh' => $this->faker->randomFloat(4, 0.15, 0.30), // prix du kWh
            'tariff_subscription' => $this->faker->randomFloat(2, 10, 50), // abonnement fixe
            'advance_amount' => $this->faker->randomFloat(2, 50, 200), // acompte payé
            'advance_frequency' => $this->faker->randomElement(['monthly', 'quarterly']), // fréquence acompte
        ];
    }

    /**
     * État spécifique : tarif BASIC.
     */
    public function basicTariff(): self
    {
        return $this->state(function () {
            return [
                'tariff_code' => 'BASIC',
                'tariff_price_kwh' => 0.20,
                'tariff_subscription' => 15,
            ];
        });
    }

    /**
     * État spécifique : tarif PREMIUM.
     */
    public function premiumTariff(): self
    {
        return $this->state(function () {
            return [
                'tariff_code' => 'PREMIUM',
                'tariff_price_kwh' => 0.25,
                'tariff_subscription' => 25,
            ];
        });
    }
}
