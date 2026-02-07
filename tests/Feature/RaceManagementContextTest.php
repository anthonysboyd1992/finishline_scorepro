<?php

use App\Models\Event;
use App\Models\RaceClass;
use App\Models\Sanction;
use App\Models\Track;
use App\Support\RaceManagementContext;

beforeEach(function () {
    $this->track = Track::factory()->create();
    $this->sanction = Sanction::factory()->create();
    $this->classes = RaceClass::factory()->count(3)->create();
    $this->event = Event::factory()->for($this->track)->create();

    $this->event->sanctions()->attach($this->sanction);
    $this->event->raceClasses()->attach($this->classes);

    session(['current_sanction_id' => $this->sanction->id]);
});

it('loads events for the current sanction', function () {
    $ctx = new RaceManagementContext(
        event: $this->event,
        currentPage: 'index',
    );

    expect($ctx->events)->toHaveCount(1)
        ->and($ctx->events->first()->id)->toBe($this->event->id);
});

it('loads classes for the current event', function () {
    $ctx = new RaceManagementContext(
        event: $this->event,
        currentPage: 'index',
    );

    expect($ctx->classes)->toHaveCount(3);
});

it('generates correct sidebar items', function () {
    $ctx = new RaceManagementContext(
        event: $this->event,
        raceClass: $this->classes->first(),
        raceType: 'heat',
        currentPage: 'lineups',
    );

    $items = $ctx->sidebarItems();

    expect($items)->toHaveCount(7)
        ->and($items[0]['key'])->toBe('index')
        ->and($items[0]['label'])->toBe('Setup')
        ->and($items[4]['key'])->toBe('lineups')
        ->and($items[4]['label'])->toBe('Lineups');

    // URLs should contain the event ID
    foreach ($items as $item) {
        expect($item['href'])->toContain('/race-management/'.$this->event->id);
    }
});

it('generates correct event dropdown data', function () {
    $ctx = new RaceManagementContext(
        event: $this->event,
        currentPage: 'index',
    );

    $dropdown = $ctx->eventsForDropdown();

    expect($dropdown)->toHaveCount(1)
        ->and($dropdown[0]['id'])->toBe($this->event->id)
        ->and($dropdown[0]['name'])->toBe($this->event->name)
        ->and($dropdown[0]['wire'])->toBeTrue();
});

it('generates correct class dropdown data', function () {
    $ctx = new RaceManagementContext(
        event: $this->event,
        raceClass: $this->classes->first(),
        currentPage: 'entries',
    );

    $dropdown = $ctx->classesForDropdown();

    expect($dropdown)->toHaveCount(3);

    foreach ($dropdown as $item) {
        expect($item)->toHaveKeys(['id', 'name', 'href', 'wire'])
            ->and($item['wire'])->toBeTrue()
            ->and($item['href'])->toContain('/race-management/'.$this->event->id.'/entries/');
    }
});

it('generates correct race type data', function () {
    $ctx = new RaceManagementContext(
        event: $this->event,
        raceClass: $this->classes->first(),
        raceType: 'heat',
        currentPage: 'lineups',
    );

    $raceTypes = $ctx->raceTypes();

    expect($raceTypes)->toHaveCount(6)
        ->and($raceTypes[0]['id'])->toBe('hotlaps')
        ->and($raceTypes[0]['name'])->toBe('Hotlaps')
        ->and($raceTypes[2]['id'])->toBe('heat')
        ->and($raceTypes[2]['name'])->toBe('Heat');

    // Each race type should have an href with the race type slug
    foreach ($raceTypes as $rt) {
        expect($rt['href'])->toContain('/lineups/'.$this->classes->first()->id.'/'.$rt['id']);
    }
});

it('returns the correct race type label', function () {
    $ctx = new RaceManagementContext(
        event: $this->event,
        raceType: 'qualifying',
        currentPage: 'lineups',
    );

    expect($ctx->raceTypeLabel())->toBe('Qualifying');
});

it('defaults race type label to Heat for unknown slugs', function () {
    $ctx = new RaceManagementContext(
        event: $this->event,
        raceType: 'unknown',
        currentPage: 'lineups',
    );

    expect($ctx->raceTypeLabel())->toBe('Heat');
});

it('generates event-switching URLs that preserve the current page', function () {
    $otherEvent = Event::factory()->for($this->track)->create();
    $otherEvent->sanctions()->attach($this->sanction);
    $otherClass = RaceClass::factory()->create();
    $otherEvent->raceClasses()->attach($otherClass);

    $ctx = new RaceManagementContext(
        event: $this->event,
        raceClass: $this->classes->first(),
        raceType: 'heat',
        currentPage: 'lineups',
    );

    $url = $ctx->urlForEvent($otherEvent);

    expect($url)->toContain('/race-management/'.$otherEvent->id.'/lineups/');
});

it('generates class-switching URLs that preserve event and race type', function () {
    $ctx = new RaceManagementContext(
        event: $this->event,
        raceClass: $this->classes->first(),
        raceType: 'heat',
        currentPage: 'lineups',
    );

    $newClass = $this->classes->last();
    $url = $ctx->urlForClass($newClass);

    expect($url)->toContain('/race-management/'.$this->event->id.'/lineups/'.$newClass->id.'/heat');
});

it('generates race-type-switching URLs', function () {
    $ctx = new RaceManagementContext(
        event: $this->event,
        raceClass: $this->classes->first(),
        raceType: 'heat',
        currentPage: 'lineups',
    );

    $url = $ctx->urlForRaceType('qualifying');

    expect($url)->toContain('/race-management/'.$this->event->id.'/lineups/'.$this->classes->first()->id.'/qualifying');
});

it('returns null for currentClassArray when no class is set', function () {
    $ctx = new RaceManagementContext(
        event: $this->event,
        currentPage: 'index',
    );

    expect($ctx->currentClassArray())->toBeNull();
});

it('returns null for currentRaceTypeArray when no race type is set', function () {
    $ctx = new RaceManagementContext(
        event: $this->event,
        currentPage: 'index',
    );

    expect($ctx->currentRaceTypeArray())->toBeNull();
});
