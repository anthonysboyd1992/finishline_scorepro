<?php

use App\Models\Competitor;
use App\Models\Entry;
use App\Models\Event;
use App\Models\RaceClass;
use App\Models\RaceResult;
use App\Models\Sanction;
use App\Models\Schedule;
use App\Models\Season;
use App\Models\Track;

it('creates a track with events', function () {
    $track = Track::factory()->create();
    $event = Event::factory()->for($track)->create();

    expect($track->events)->toHaveCount(1)
        ->and($track->events->first()->id)->toBe($event->id);
});

it('creates an event belonging to a track', function () {
    $event = Event::factory()->create();

    expect($event->track)->toBeInstanceOf(Track::class)
        ->and($event->track->id)->toBe($event->track_id);
});

it('attaches sanctions to an event via pivot', function () {
    $event = Event::factory()->create();
    $sanctions = Sanction::factory()->count(2)->create();

    $event->sanctions()->attach($sanctions);

    expect($event->sanctions)->toHaveCount(2)
        ->and($event->sanctions->pluck('id')->sort()->values()->all())
        ->toBe($sanctions->pluck('id')->sort()->values()->all());
});

it('attaches race classes to an event via pivot', function () {
    $event = Event::factory()->create();
    $classes = RaceClass::factory()->count(3)->create();

    $event->raceClasses()->attach($classes);

    expect($event->raceClasses)->toHaveCount(3);
});

it('allows a sanction to access its events', function () {
    $sanction = Sanction::factory()->create();
    $events = Event::factory()->count(2)->create();

    $sanction->events()->attach($events);

    expect($sanction->events)->toHaveCount(2);
});

it('allows a race class to access its events', function () {
    $raceClass = RaceClass::factory()->create();
    $events = Event::factory()->count(2)->create();

    $raceClass->events()->attach($events);

    expect($raceClass->events)->toHaveCount(2);
});

it('casts event_date to a Carbon instance', function () {
    $event = Event::factory()->create(['event_date' => '2026-06-15']);

    expect($event->event_date)->toBeInstanceOf(\Carbon\CarbonImmutable::class)
        ->and($event->event_date->format('Y-m-d'))->toBe('2026-06-15');
});

it('stores track details correctly', function () {
    $track = Track::factory()->create([
        'name' => 'Test Speedway',
        'city' => 'Lima',
        'state' => 'OH',
        'surface_type' => 'dirt',
        'track_length' => '1/4 mile',
    ]);

    expect($track->name)->toBe('Test Speedway')
        ->and($track->city)->toBe('Lima')
        ->and($track->state)->toBe('OH')
        ->and($track->surface_type)->toBe('dirt')
        ->and($track->track_length)->toBe('1/4 mile');
});

it('stores sanction type correctly', function () {
    $sanction = Sanction::factory()->create([
        'name' => 'NRA Sprint',
        'type' => 'series',
    ]);

    expect($sanction->name)->toBe('NRA Sprint')
        ->and($sanction->type)->toBe('series');
});

it('creates an entry belonging to an event and race class', function () {
    $entry = Entry::factory()->create();

    expect($entry->event)->toBeInstanceOf(Event::class)
        ->and($entry->raceClass)->toBeInstanceOf(RaceClass::class);
});

it('accesses entries from an event', function () {
    $event = Event::factory()->create();
    Entry::factory()->count(3)->for($event)->create();

    expect($event->entries)->toHaveCount(3);
});

it('accesses entries from a race class', function () {
    $raceClass = RaceClass::factory()->create();
    Entry::factory()->count(2)->create(['race_class_id' => $raceClass->id]);

    expect($raceClass->entries)->toHaveCount(2);
});

it('creates a race result belonging to an entry', function () {
    $result = RaceResult::factory()->create();

    expect($result->entry)->toBeInstanceOf(Entry::class);
});

it('accesses race results from an entry', function () {
    $entry = Entry::factory()->create();
    RaceResult::factory()->count(3)->for($entry)->create();

    expect($entry->raceResults)->toHaveCount(3);
});

it('stores race result data correctly', function () {
    $result = RaceResult::factory()->qualifying()->create([
        'laps_completed' => 10,
        'best_lap_time' => 15.234,
        'total_time' => 160.500,
        'finishing_position' => 3,
        'points' => 91,
    ]);

    expect($result->race_type)->toBe('Qualifying')
        ->and($result->laps_completed)->toBe(10)
        ->and((float) $result->best_lap_time)->toBe(15.234)
        ->and((float) $result->total_time)->toBe(160.500)
        ->and($result->finishing_position)->toBe(3)
        ->and($result->points)->toBe(91);
});

// --- Season relationship tests ---

it('creates a season belonging to a sanction', function () {
    $season = Season::factory()->create();

    expect($season->sanction)->toBeInstanceOf(Sanction::class);
});

it('accesses seasons from a sanction', function () {
    $sanction = Sanction::factory()->create();
    Season::factory()->for($sanction)->create(['year' => 2024]);
    Season::factory()->for($sanction)->create(['year' => 2025]);
    Season::factory()->for($sanction)->create(['year' => 2026]);

    expect($sanction->seasons)->toHaveCount(3);
});

it('marks a season as active', function () {
    $season = Season::factory()->active()->create();

    expect($season->is_active)->toBeTrue();
});

// --- Schedule relationship tests ---

it('creates a schedule belonging to a season', function () {
    $schedule = Schedule::factory()->create();

    expect($schedule->season)->toBeInstanceOf(Season::class);
});

it('accesses schedules from a season', function () {
    $season = Season::factory()->create();
    Schedule::factory()->count(2)->for($season)->create();

    expect($season->schedules)->toHaveCount(2);
});

it('attaches events to a schedule via pivot', function () {
    $schedule = Schedule::factory()->create();
    $events = Event::factory()->count(3)->create();

    $schedule->events()->attach($events);

    expect($schedule->events)->toHaveCount(3);
});

it('accesses schedules from an event', function () {
    $event = Event::factory()->create();
    $schedules = Schedule::factory()->count(2)->create();

    $event->schedules()->attach($schedules);

    expect($event->schedules)->toHaveCount(2);
});

// --- Competitor relationship tests ---

it('creates a competitor with all fields', function () {
    $competitor = Competitor::factory()->create([
        'first_name' => 'John',
        'last_name' => 'Doe',
        'car_number' => '42',
        'city' => 'Lima',
        'state' => 'OH',
        'country' => 'US',
    ]);

    expect($competitor->first_name)->toBe('John')
        ->and($competitor->last_name)->toBe('Doe')
        ->and($competitor->car_number)->toBe('42')
        ->and($competitor->fullName())->toBe('John Doe')
        ->and($competitor->displayName())->toBe('Doe, John');
});

it('accesses entries from a competitor', function () {
    $competitor = Competitor::factory()->create();
    Entry::factory()->count(3)->create(['competitor_id' => $competitor->id]);

    expect($competitor->entries)->toHaveCount(3);
});

it('creates an entry linked to a competitor', function () {
    $competitor = Competitor::factory()->create();
    $entry = Entry::factory()->create(['competitor_id' => $competitor->id]);

    expect($entry->competitor)->toBeInstanceOf(Competitor::class)
        ->and($entry->competitor->id)->toBe($competitor->id);
});

it('stores pill number on an entry', function () {
    $entry = Entry::factory()->create(['pill_number' => 7]);

    expect($entry->pill_number)->toBe(7);
});

// --- Event displayName() ---

it('shows name and date when both are set', function () {
    $event = Event::factory()->create([
        'name' => 'Spring Classic',
        'event_date' => '2026-06-15',
    ]);

    expect($event->displayName())->toBe('Spring Classic - Jun 15, 2026');
});

it('shows date only when name is null', function () {
    $event = Event::factory()->create([
        'name' => null,
        'event_date' => '2026-06-15',
    ]);

    expect($event->displayName())->toBe('Jun 15, 2026');
});

// --- Full chain: Sanction -> Season -> Schedule -> Event ---

it('traverses the full sanction to event chain', function () {
    $sanction = Sanction::factory()->create();
    $season = Season::factory()->for($sanction)->active()->create(['year' => 2026]);
    $schedule = Schedule::factory()->for($season)->create(['year' => 2026]);
    $event = Event::factory()->create();
    $schedule->events()->attach($event);

    $loadedSeason = $sanction->seasons()->where('is_active', true)->first();
    $loadedSchedule = $loadedSeason->schedules()->first();
    $loadedEvent = $loadedSchedule->events()->first();

    expect($loadedSeason->id)->toBe($season->id)
        ->and($loadedSchedule->id)->toBe($schedule->id)
        ->and($loadedEvent->id)->toBe($event->id);
});
