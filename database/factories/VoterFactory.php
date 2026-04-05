<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Voter>
 */
class VoterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'surname' => fake()->lastName(),
            'dni' => fake()->unique()->numberBetween(10000000, 99999999),
            'date_of_birth' => fake()->date(),
            'mesa_id' => fake()->numberBetween(1, 2),
            'active' => fake()->boolean(),
        ];
    }
}
