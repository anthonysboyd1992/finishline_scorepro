<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LineupScript>
 */
class LineupScriptFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'slug' => fake()->unique()->slug(2),
            'race_type' => fake()->randomElement(['Heat', 'Qualifying', 'A-Feature', 'B-Feature']),
            'description' => fake()->sentence(),
            'icon' => 'grid',
            'is_active' => true,
            'sort_order' => 0,
            'config' => null,
        ];
    }
}
