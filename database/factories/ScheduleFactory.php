<?php

namespace Database\Factories;

use App\Models\Schedule;
use App\Models\Season;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Schedule>
 */
class ScheduleFactory extends Factory
{
    protected $model = Schedule::class;

    public function definition(): array
    {
        return [
            'season_id' => Season::factory(),
            'name' => fake()->randomElement(['Regular Season', 'Championship Series', 'Practice Schedule', 'Points Race']),
            'year' => fake()->numberBetween(2024, 2027),
        ];
    }
}
