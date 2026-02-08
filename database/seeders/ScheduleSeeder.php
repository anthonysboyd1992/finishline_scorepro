<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Schedule;
use App\Models\Season;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $activeSeasons = Season::query()->where('is_active', true)->get();

        foreach ($activeSeasons as $season) {
            $schedule = Schedule::query()->updateOrCreate(
                ['season_id' => $season->id, 'name' => 'Regular Season'],
                ['year' => $season->year],
            );

            // Attach all existing events to this schedule
            $events = Event::all();
            $schedule->events()->syncWithoutDetaching($events->pluck('id'));
        }
    }
}
