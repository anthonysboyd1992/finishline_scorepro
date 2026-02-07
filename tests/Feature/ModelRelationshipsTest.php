<?php

use App\Models\Event;
use App\Models\RaceClass;
use App\Models\Sanction;
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
