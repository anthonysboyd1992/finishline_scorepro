<?php

namespace Database\Factories;

use App\Models\RaceClass;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RaceClass>
 */
class RaceClassFactory extends Factory
{
    protected $model = RaceClass::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->word().' Class',
        ];
    }
}
