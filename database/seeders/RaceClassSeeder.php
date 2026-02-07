<?php

namespace Database\Seeders;

use App\Models\RaceClass;
use Illuminate\Database\Seeder;

class RaceClassSeeder extends Seeder
{
    private const CLASSES = [
        'Stock Novice',
        'Stock Sportsman',
        'Modified',
        '17.5 Blinky',
        'Open Modified',
    ];

    public function run(): void
    {
        foreach (self::CLASSES as $name) {
            RaceClass::query()->updateOrCreate(['name' => $name]);
        }
    }
}
