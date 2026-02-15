<?php

use App\Models\Event;
use App\Models\RaceClass;
use App\Models\Sanction;
use App\Models\Track;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->track = Track::factory()->create();
    $this->sanction = Sanction::factory()->create();
    $this->event = Event::factory()->for($this->track)->create();
    $this->raceClass = RaceClass::factory()->create();

    $this->event->sanctions()->attach($this->sanction);
    $this->event->raceClasses()->attach($this->raceClass);

    session(['current_sanction_id' => $this->sanction->id]);
});

it('redirects setup page to first class when no class provided', function () {
    $this->actingAs($this->user)
        ->get(route('race-management.index', ['event' => $this->event]))
        ->assertRedirect(route('race-management.index', ['event' => $this->event, 'raceClass' => $this->raceClass]));
});

it('loads the setup page with class parameter', function () {
    $this->actingAs($this->user)
        ->get(route('race-management.index', ['event' => $this->event, 'raceClass' => $this->raceClass]))
        ->assertOk()
        ->assertSee('Config');
});

it('loads the check ins page with event and class', function () {
    $this->actingAs($this->user)
        ->get(route('race-management.entries', ['event' => $this->event, 'raceClass' => $this->raceClass]))
        ->assertOk()
        ->assertSee('Check Ins');
});

it('loads the races page with event and class', function () {
    $this->actingAs($this->user)
        ->get(route('race-management.races', ['event' => $this->event, 'raceClass' => $this->raceClass]))
        ->assertOk()
        ->assertSee('Races');
});

it('loads the race order page with event and class', function () {
    $this->actingAs($this->user)
        ->get(route('race-management.race-order', ['event' => $this->event, 'raceClass' => $this->raceClass]))
        ->assertOk()
        ->assertSee('Race Order');
});

it('loads the line ups page with event, class, and race type', function () {
    $this->actingAs($this->user)
        ->get(route('race-management.lineups', ['event' => $this->event, 'raceClass' => $this->raceClass, 'raceType' => 'heat']))
        ->assertOk()
        ->assertSee('Line Ups');
});

it('loads the scoring page with event, class, and race type', function () {
    $this->actingAs($this->user)
        ->get(route('race-management.results', ['event' => $this->event, 'raceClass' => $this->raceClass, 'raceType' => 'heat']))
        ->assertOk()
        ->assertSee('Scoring');
});

it('loads the reports page with event, class, and race type', function () {
    $this->actingAs($this->user)
        ->get(route('race-management.points-payout', ['event' => $this->event, 'raceClass' => $this->raceClass, 'raceType' => 'heat']))
        ->assertOk()
        ->assertSee('Reports');
});

it('redirects guests from race management pages', function () {
    $this->get(route('race-management.index', ['event' => $this->event]))
        ->assertRedirect(route('login'));
});

it('returns 404 for non-existent event', function () {
    $this->actingAs($this->user)
        ->get('/race-management/99999/setup')
        ->assertNotFound();
});

it('returns 404 for non-existent race class', function () {
    $this->actingAs($this->user)
        ->get('/race-management/'.$this->event->id.'/entries/99999')
        ->assertNotFound();
});

it('shows the event name on the setup page', function () {
    $this->actingAs($this->user)
        ->get(route('race-management.index', ['event' => $this->event, 'raceClass' => $this->raceClass]))
        ->assertOk()
        ->assertSee($this->event->displayName());
});

it('shows the class name on pages with class context', function () {
    $this->actingAs($this->user)
        ->get(route('race-management.entries', ['event' => $this->event, 'raceClass' => $this->raceClass]))
        ->assertOk()
        ->assertSee($this->raceClass->name);
});
