<?php

namespace Database\Seeders;

use App\Models\Entry;
use App\Models\Event;
use App\Models\RaceClass;
use App\Models\RaceResult;
use Illuminate\Database\Seeder;

class RaceResultSeeder extends Seeder
{
    public function run(): void
    {
        $event = Event::query()->firstOrFail();
        $raceClass = RaceClass::query()->firstOrFail();

        $entries = Entry::query()
            ->where('event_id', $event->id)
            ->where('race_class_id', $raceClass->id)
            ->get();

        // Create qualifying results for most entries (leave a few without to test edge cases)
        $position = 1;

        foreach ($entries as $entry) {
            // Skip a couple entries to simulate no qualifying time
            if (in_array($entry->car_number, ['67', '16B'])) {
                continue;
            }

            $bestLap = round(14.5 + ($position * 0.15) + (mt_rand(0, 100) / 500), 3);
            $laps = mt_rand(8, 12);

            RaceResult::query()->updateOrCreate(
                [
                    'entry_id' => $entry->id,
                    'race_type' => 'Qualifying',
                    'race_number' => 1,
                ],
                [
                    'laps_completed' => $laps,
                    'best_lap_time' => $bestLap,
                    'total_time' => round($bestLap * $laps * (1.02 + mt_rand(0, 5) / 100), 3),
                    'finishing_position' => $position,
                    'points' => 0,
                ],
            );

            $position++;
        }
    }
}
