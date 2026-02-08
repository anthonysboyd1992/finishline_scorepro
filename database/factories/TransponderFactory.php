<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transponder>
 */
class TransponderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'number' => $this->faker->unique()->numerify('#############'),
            'alias' => strtoupper($this->faker->unique()->lexify('??')).$this->faker->numberBetween(1, 99),
            'is_rental' => false,
        ];
    }

    public function rental(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_rental' => true,
        ]);
    }
}
