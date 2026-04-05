<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\School>
 */
class SchoolFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->numerify('######'),
            'name' => $this->faker->name(),
            'address' => $this->faker->address(),
            'tables' => $this->faker->numberBetween(1,50),
            'voters' => $this->faker->numberBetween(1,300),
            'district_id' => $this->faker->numberBetween(2, 1838),

        ];
    }
}
