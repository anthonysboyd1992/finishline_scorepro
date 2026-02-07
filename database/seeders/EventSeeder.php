<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\RaceClass;
use App\Models\Sanction;
use App\Models\Track;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $track = Track::query()->firstOrFail();
        $sanctions = Sanction::all();
        $raceClasses = RaceClass::all();

        $events = [
            ['name' => 'Saturday Racing', 'event_date' => '2026-03-15'],
            ['name' => 'Sunday Finals', 'event_date' => '2026-03-16'],
            ['name' => 'Practice Day', 'event_date' => '2026-03-14'],
        ];

        foreach ($events as $eventData) {
            $event = Event::query()->updateOrCreate(
                ['name' => $eventData['name'], 'track_id' => $track->id],
                ['event_date' => $eventData['event_date']],
            );

            $event->sanctions()->syncWithoutDetaching($sanctions->pluck('id'));
            $event->raceClasses()->syncWithoutDetaching($raceClasses->pluck('id'));
        }
    }
}
