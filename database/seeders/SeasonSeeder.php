<?php

namespace Database\Seeders;

use App\Models\Sanction;
use App\Models\Season;
use Illuminate\Database\Seeder;

class SeasonSeeder extends Seeder
{
    public function run(): void
    {
        $sanctions = Sanction::all();

        foreach ($sanctions as $sanction) {
            Season::query()->updateOrCreate(
                ['sanction_id' => $sanction->id, 'year' => 2026],
                ['is_active' => true],
            );

            Season::query()->updateOrCreate(
                ['sanction_id' => $sanction->id, 'year' => 2025],
                ['is_active' => false],
            );
        }
    }
}
