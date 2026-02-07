<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Track;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Event>
 */
class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        return [
            'track_id' => Track::factory(),
            'name' => fake()->randomElement(['Saturday Racing', 'Sunday Finals', 'Practice Day', 'Championship Night']),
            'event_date' => fake()->dateTimeBetween('now', '+6 months')->format('Y-m-d'),
        ];
    }
}
