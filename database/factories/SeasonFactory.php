<?php

namespace Database\Factories;

use App\Models\Sanction;
use App\Models\Season;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Season>
 */
class SeasonFactory extends Factory
{
    protected $model = Season::class;

    public function definition(): array
    {
        return [
            'sanction_id' => Sanction::factory(),
            'year' => fake()->numberBetween(2024, 2027),
            'is_active' => false,
        ];
    }

    /**
     * Mark the season as active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }
}
