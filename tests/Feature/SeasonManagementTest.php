<?php

use App\Models\Event;
use App\Models\RaceClass;
use App\Models\Sanction;
use App\Models\Schedule;
use App\Models\Season;
use App\Models\Track;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
    $this->sanction = Sanction::factory()->create();
    session(['current_sanction_id' => $this->sanction->id]);
});

// --- Season CRUD ---

it('renders the seasons page', function () {
    $this->get(route('seasons.index'))->assertOk();
});

it('lists seasons for the current sanction', function () {
    Season::factory()->for($this->sanction)->create(['year' => 2026, 'is_active' => true]);
    Season::factory()->for($this->sanction)->create(['year' => 2025, 'is_active' => false]);

    Livewire::test('seasons.season-manager')
        ->assertSee('2026 Season')
        ->assertSee('2025 Season');
});

it('creates a new season', function () {
    Livewire::test('seasons.season-manager')
        ->call('startCreating')
        ->set('formYear', 2027)
        ->set('formIsActive', true)
        ->call('saveSeason');

    $this->assertDatabaseHas('seasons', [
        'sanction_id' => $this->sanction->id,
        'year' => 2027,
        'is_active' => true,
    ]);
});

it('deactivates other seasons when activating one', function () {
    $existing = Season::factory()->for($this->sanction)->create(['year' => 2025, 'is_active' => true]);

    Livewire::test('seasons.season-manager')
        ->call('startCreating')
        ->set('formYear', 2026)
        ->set('formIsActive', true)
        ->call('saveSeason');

    expect($existing->fresh()->is_active)->toBeFalse();
});

it('edits an existing season', function () {
    $season = Season::factory()->for($this->sanction)->create(['year' => 2025]);

    Livewire::test('seasons.season-manager')
        ->call('selectSeason', $season->id)
        ->call('startEditing')
        ->set('formYear', 2024)
        ->call('saveSeason');

    expect($season->fresh()->year)->toBe(2024);
});

it('deletes a season', function () {
    $season = Season::factory()->for($this->sanction)->create(['year' => 2025]);

    Livewire::test('seasons.season-manager')
        ->call('selectSeason', $season->id)
        ->call('deleteSeason');

    $this->assertDatabaseMissing('seasons', ['id' => $season->id]);
});

// --- Schedule CRUD ---

it('renders the schedules page', function () {
    $this->get(route('seasons.schedules'))->assertOk();
});

it('creates a new schedule', function () {
    $season = Season::factory()->for($this->sanction)->active()->create(['year' => 2026]);
    session(['current_season_id' => $season->id]);

    Livewire::test('seasons.schedule-manager', ['seasonId' => $season->id])
        ->call('startCreating')
        ->set('formName', 'Championship Series')
        ->set('formYear', 2026)
        ->call('saveSchedule');

    $this->assertDatabaseHas('schedules', [
        'season_id' => $season->id,
        'name' => 'Championship Series',
        'year' => 2026,
    ]);
});

it('deletes a schedule', function () {
    $season = Season::factory()->for($this->sanction)->create(['year' => 2026]);
    $schedule = Schedule::factory()->for($season)->create();

    Livewire::test('seasons.schedule-manager', ['seasonId' => $season->id])
        ->call('selectSchedule', $schedule->id)
        ->call('deleteSchedule');

    $this->assertDatabaseMissing('schedules', ['id' => $schedule->id]);
});

// --- Event CRUD ---

it('renders the events page', function () {
    $this->get(route('seasons.events'))->assertOk();
});

it('creates a new event in season context', function () {
    $season = Season::factory()->for($this->sanction)->active()->create(['year' => 2026]);
    $schedule = Schedule::factory()->for($season)->create(['year' => 2026]);
    $track = Track::factory()->create();
    $classes = RaceClass::factory()->count(2)->create();

    Livewire::test('seasons.event-manager', ['seasonId' => $season->id])
        ->call('startCreating')
        ->set('formName', 'Test Race Night')
        ->set('formDate', '2026-07-04')
        ->set('formTrackId', $track->id)
        ->set('formScheduleId', $schedule->id)
        ->set('formClassIds', $classes->pluck('id')->all())
        ->call('saveEvent');

    $event = Event::query()->where('name', 'Test Race Night')->first();
    expect($event)->not->toBeNull()
        ->and($event->track_id)->toBe($track->id)
        ->and($event->raceClasses)->toHaveCount(2)
        ->and($event->schedules)->toHaveCount(1);
});

it('creates an event without a name (date-only display)', function () {
    $season = Season::factory()->for($this->sanction)->active()->create(['year' => 2026]);
    $schedule = Schedule::factory()->for($season)->create(['year' => 2026]);
    $track = Track::factory()->create();

    Livewire::test('seasons.event-manager', ['seasonId' => $season->id])
        ->call('startCreating')
        ->assertSet('isCreating', true)
        ->set('formName', '')
        ->set('formDate', '2026-08-15')
        ->set('formTrackId', $track->id)
        ->set('formScheduleId', $schedule->id)
        ->call('saveEvent')
        ->assertHasNoErrors();

    $event = Event::query()->where('track_id', $track->id)->latest('id')->first();
    expect($event)->not->toBeNull()
        ->and($event->name)->toBeNull()
        ->and($event->displayName())->toBe('Aug 15, 2026');
});

// --- Track CRUD ---

it('renders the tracks page', function () {
    $this->get(route('seasons.tracks'))->assertOk();
});

it('creates a new track', function () {
    Livewire::test('seasons.track-manager')
        ->call('startCreating')
        ->set('formName', 'Eldora Speedway')
        ->set('formCity', 'Rossburg')
        ->set('formState', 'OH')
        ->set('formSurfaceType', 'dirt')
        ->set('formTrackLength', '1/2 mile')
        ->call('saveTrack');

    $this->assertDatabaseHas('tracks', [
        'name' => 'Eldora Speedway',
        'city' => 'Rossburg',
        'state' => 'OH',
        'surface_type' => 'dirt',
    ]);
});

it('edits a track', function () {
    $track = Track::factory()->create(['name' => 'Old Name']);

    Livewire::test('seasons.track-manager')
        ->call('selectTrack', $track->id)
        ->call('startEditing')
        ->set('formName', 'New Name')
        ->call('saveTrack');

    expect($track->fresh()->name)->toBe('New Name');
});

it('deletes a track', function () {
    $track = Track::factory()->create();

    Livewire::test('seasons.track-manager')
        ->call('selectTrack', $track->id)
        ->call('deleteTrack');

    $this->assertDatabaseMissing('tracks', ['id' => $track->id]);
});

it('searches tracks by name', function () {
    Track::factory()->create(['name' => 'Eldora Speedway']);
    Track::factory()->create(['name' => 'Limaland Motorsports Park']);

    Livewire::test('seasons.track-manager')
        ->set('search', 'Eldora')
        ->assertSee('Eldora Speedway')
        ->assertDontSee('Limaland Motorsports Park');
});
