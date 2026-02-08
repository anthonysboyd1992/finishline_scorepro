<?php

use App\Models\Event;
use App\Models\Sanction;
use App\Models\Track;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->track = Track::factory()->create();
});

it('switches sanction and stores in session', function () {
    $sanction = Sanction::factory()->create();
    $event = Event::factory()->for($this->track)->create();
    $event->sanctions()->attach($sanction);

    $this->actingAs($this->user)
        ->post(route('sanction.switch', $sanction))
        ->assertRedirect();

    expect(session('current_sanction_id'))->toBe($sanction->id);
});

it('redirects to dashboard after sanction switch', function () {
    $sanction = Sanction::factory()->create();
    $event = Event::factory()->for($this->track)->create(['event_date' => '2026-01-01']);
    $event->sanctions()->attach($sanction);

    $response = $this->actingAs($this->user)
        ->post(route('sanction.switch', $sanction));

    $response->assertRedirect(route('dashboard'));
});

it('redirects to dashboard if sanction has no events', function () {
    $sanction = Sanction::factory()->create();

    $this->actingAs($this->user)
        ->post(route('sanction.switch', $sanction))
        ->assertRedirect(route('dashboard'));
});

it('redirects race-management entry to first event for session sanction', function () {
    $sanction = Sanction::factory()->create();
    $event = Event::factory()->for($this->track)->create();
    $event->sanctions()->attach($sanction);
    session(['current_sanction_id' => $sanction->id]);

    $this->actingAs($this->user)
        ->get(route('race-management.entry'))
        ->assertRedirect();
});

it('redirects race-management entry to dashboard when no sanctions exist', function () {
    $this->actingAs($this->user)
        ->get(route('race-management.entry'))
        ->assertRedirect(route('dashboard'));
});
