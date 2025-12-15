<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Client;

/**
 * @extends Factory<Client>
 */
class ClientFactory extends Factory
{
    protected $model = Client::class;

    public function definition(): array
    {
        return [
            'firstname' => $this->faker->firstName,
            'lastname'  => $this->faker->lastName,
            'email'     => $this->faker->unique()->safeEmail,
            'phone'     => $this->faker->randomElement([
                $this->faker->numerify('+32########'),
                $this->faker->numerify('0########'),
            ])
        ];
    }
}
