<?php

namespace Database\Factories;

use App\Models\Entry;
use App\Models\Event;
use App\Models\RaceClass;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Entry>
 */
class EntryFactory extends Factory
{
    protected $model = Entry::class;

    public function definition(): array
    {
        return [
            'event_id' => Event::factory(),
            'race_class_id' => RaceClass::factory(),
            'car_number' => fake()->unique()->numerify('##').fake()->randomElement(['', 'X', 'B', 'H']),
            'driver_name' => fake()->lastName().', '.fake()->firstName(),
            'city' => fake()->city().', '.fake()->stateAbbr(),
        ];
    }
}
