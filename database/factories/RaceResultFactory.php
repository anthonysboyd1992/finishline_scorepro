<?php

namespace Database\Factories;

use App\Models\Entry;
use App\Models\RaceResult;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RaceResult>
 */
class RaceResultFactory extends Factory
{
    protected $model = RaceResult::class;

    public function definition(): array
    {
        return [
            'entry_id' => Entry::factory(),
            'race_type' => fake()->randomElement(['Qualifying', 'Heat', 'A-Feature']),
            'race_number' => 1,
            'laps_completed' => fake()->numberBetween(8, 15),
            'best_lap_time' => fake()->randomFloat(3, 14.0, 19.0),
            'total_time' => fake()->randomFloat(3, 120.0, 180.0),
            'finishing_position' => fake()->numberBetween(1, 10),
            'points' => fake()->numberBetween(0, 100),
        ];
    }

    /**
     * Set this result as a qualifying result.
     */
    public function qualifying(int $raceNumber = 1): static
    {
        return $this->state(fn (array $attributes) => [
            'race_type' => 'Qualifying',
            'race_number' => $raceNumber,
        ]);
    }
}
