<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\party>
 */
class partyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->bothify('??###'),
            'name' => $this->faker->company(),
            'acronym' => strtoupper($this->faker->lexify('???')),
            'voter_id' => $this->faker->numberBetween(2,51),
            'logo_path' => $this->faker->imageUrl(200, 200, 'politics', true, 'Logo'),
        ];
    }
}
