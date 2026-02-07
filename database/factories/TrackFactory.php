<?php

namespace Database\Factories;

use App\Models\Track;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Track>
 */
class TrackFactory extends Factory
{
    protected $model = Track::class;

    public function definition(): array
    {
        return [
            'name' => fake()->company().' Speedway',
            'city' => fake()->city(),
            'state' => fake()->stateAbbr(),
            'address' => fake()->streetAddress(),
            'surface_type' => fake()->randomElement(['dirt', 'asphalt', 'clay']),
            'track_length' => fake()->randomElement(['1/4 mile', '3/8 mile', '1/2 mile', '1 mile']),
        ];
    }
}
