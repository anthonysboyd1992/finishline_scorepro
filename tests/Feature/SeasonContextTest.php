<?php

use App\Models\Event;
use App\Models\RaceClass;
use App\Models\Sanction;
use App\Models\Schedule;
use App\Models\Season;
use App\Models\Track;
use App\Models\User;
use App\Support\RaceManagementContext;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);

    $this->sanction = Sanction::factory()->create();
    $this->season = Season::factory()->for($this->sanction)->active()->create(['year' => 2026]);
    $this->schedule = Schedule::factory()->for($this->season)->create(['year' => 2026]);

    $this->track = Track::factory()->create();
    $this->event = Event::factory()->create(['track_id' => $this->track->id]);
    $this->schedule->events()->attach($this->event);

    $this->raceClass = RaceClass::factory()->create();
    $this->event->raceClasses()->attach($this->raceClass);

    session(['current_sanction_id' => $this->sanction->id]);
});

it('loads the active season automatically', function () {
    $ctx = new RaceManagementContext(
        event: $this->event,
        raceClass: $this->raceClass,
        currentPage: 'entries',
    );

    expect($ctx->season->id)->toBe($this->season->id)
        ->and($ctx->season->is_active)->toBeTrue();
});

it('loads events from the season schedules', function () {
    $ctx = new RaceManagementContext(
        event: $this->event,
        raceClass: $this->raceClass,
        currentPage: 'entries',
    );

    expect($ctx->events)->toHaveCount(1)
        ->and($ctx->events->first()->id)->toBe($this->event->id);
});

it('provides seasons for dropdown', function () {
    Season::factory()->for($this->sanction)->create(['year' => 2025, 'is_active' => false]);

    $ctx = new RaceManagementContext(
        event: $this->event,
        raceClass: $this->raceClass,
        currentPage: 'entries',
    );

    $seasons = $ctx->seasonsForDropdown();
    expect($seasons)->toHaveCount(2)
        ->and($seasons[0]['year'])->toBe(2026)
        ->and($seasons[0]['isActive'])->toBeTrue();
});

it('provides current season array', function () {
    $ctx = new RaceManagementContext(
        event: $this->event,
        raceClass: $this->raceClass,
        currentPage: 'entries',
    );

    $currentSeason = $ctx->currentSeasonArray();
    expect($currentSeason)->not->toBeNull()
        ->and($currentSeason['id'])->toBe($this->season->id)
        ->and($currentSeason['year'])->toBe(2026);
});

it('stores season in session', function () {
    new RaceManagementContext(
        event: $this->event,
        raceClass: $this->raceClass,
        currentPage: 'entries',
    );

    expect(session('current_season_id'))->toBe($this->season->id);
});

it('switches season via session', function () {
    $newSeason = Season::factory()->for($this->sanction)->create(['year' => 2025]);
    session(['current_season_id' => $newSeason->id]);

    $ctx = new RaceManagementContext(
        event: $this->event,
        raceClass: $this->raceClass,
        currentPage: 'entries',
    );

    expect($ctx->season->id)->toBe($newSeason->id);
});

it('switches season via POST route and redirects to first event', function () {
    $newSeason = Season::factory()->for($this->sanction)->create(['year' => 2025]);
    $newSchedule = Schedule::factory()->for($newSeason)->create(['year' => 2025]);
    $newEvent = Event::factory()->create();
    $newSchedule->events()->attach($newEvent);

    $this->post(route('season.switch', $newSeason))
        ->assertRedirect(route('race-management.index', ['event' => $newEvent]));

    expect(session('current_season_id'))->toBe($newSeason->id);
});

it('switches season via POST route and redirects back when no events', function () {
    $newSeason = Season::factory()->for($this->sanction)->create(['year' => 2025]);

    $this->from(route('dashboard'))
        ->post(route('season.switch', $newSeason))
        ->assertRedirect(route('dashboard'));

    expect(session('current_season_id'))->toBe($newSeason->id);
});

it('renders race management pages with season context', function () {
    $this->get(route('race-management.index', ['event' => $this->event, 'raceClass' => $this->raceClass]))
        ->assertOk();

    $this->get(route('race-management.entries', ['event' => $this->event, 'raceClass' => $this->raceClass]))
        ->assertOk();
});
