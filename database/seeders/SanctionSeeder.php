<?php

namespace Database\Seeders;

use App\Models\Sanction;
use Illuminate\Database\Seeder;

class SanctionSeeder extends Seeder
{
    public function run(): void
    {
        Sanction::query()->updateOrCreate(
            ['name' => 'NRA Sprint Invaders'],
            ['type' => 'series'],
        );

        Sanction::query()->updateOrCreate(
            ['name' => 'Limaland Motorsports Park'],
            ['type' => 'track'],
        );
    }
}
