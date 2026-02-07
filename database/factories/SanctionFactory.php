<?php

namespace Database\Factories;

use App\Models\Sanction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Sanction>
 */
class SanctionFactory extends Factory
{
    protected $model = Sanction::class;

    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'type' => fake()->randomElement(['track', 'series', 'organization']),
        ];
    }
}
