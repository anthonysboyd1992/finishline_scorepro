<?php

use App\Events\PillDrawUpdated;
use App\Models\Event;
use App\Models\User;
use Illuminate\Broadcasting\PrivateChannel;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
    $this->event = Event::factory()->create();
    $this->operatorToken = session('operator_token', 'test-token');
});

it('loads the event screen page for authenticated users', function () {
    $this->get(route('event-screen', ['event' => $this->event, 'operatorToken' => $this->operatorToken]))
        ->assertOk()
        ->assertSee('Event Screen');
});

it('requires authentication to access event screen', function () {
    auth()->logout();

    $this->get(route('event-screen', ['event' => $this->event, 'operatorToken' => $this->operatorToken]))
        ->assertRedirect(route('login'));
});

it('renders the pill draw display component', function () {
    Livewire::test('event-screen.pill-draw-display', [
        'eventId' => $this->event->id,
        'operatorToken' => $this->operatorToken,
    ])
        ->assertOk()
        ->assertSee('Waiting for activity...');
});

it('handles pill draw update event', function () {
    Livewire::test('event-screen.pill-draw-display', [
        'eventId' => $this->event->id,
        'operatorToken' => $this->operatorToken,
    ])
        ->call('handlePillDrawUpdate', [
            'state' => 'dancing',
            'driverName' => 'Smith, John',
            'carNumber' => '42',
            'assignedPill' => null,
            'drawnPills' => [1, 3, 5],
            'pillMin' => 1,
            'pillMax' => 20,
        ])
        ->assertSet('mode', 'dancing')
        ->assertSet('driverName', 'Smith, John')
        ->assertSet('carNumber', '42')
        ->assertSet('drawnPills', [1, 3, 5])
        ->assertDispatched('pill-draw-update');
});

it('handles stopped state with assigned pill', function () {
    Livewire::test('event-screen.pill-draw-display', [
        'eventId' => $this->event->id,
        'operatorToken' => $this->operatorToken,
    ])
        ->call('handlePillDrawUpdate', [
            'state' => 'stopped',
            'driverName' => 'Doe, Jane',
            'carNumber' => '99',
            'assignedPill' => 7,
            'drawnPills' => [1, 3, 5, 7],
            'pillMin' => 1,
            'pillMax' => 20,
        ])
        ->assertSet('mode', 'stopped')
        ->assertSet('assignedPill', 7)
        ->assertDispatched('pill-draw-update');
});

it('resets to idle when draw exits', function () {
    $component = Livewire::test('event-screen.pill-draw-display', [
        'eventId' => $this->event->id,
        'operatorToken' => $this->operatorToken,
    ]);

    // First activate dancing
    $component->call('handlePillDrawUpdate', [
        'state' => 'dancing',
        'driverName' => 'Test, Driver',
        'carNumber' => '1',
        'assignedPill' => null,
        'drawnPills' => [],
        'pillMin' => 1,
        'pillMax' => 20,
    ]);

    // Then exit
    $component->call('handlePillDrawUpdate', [
        'state' => 'idle',
        'driverName' => '',
        'carNumber' => '',
        'assignedPill' => null,
        'drawnPills' => [],
        'pillMin' => 1,
        'pillMax' => 20,
    ])
        ->assertSet('mode', 'idle')
        ->assertSet('driverName', '');
});

it('PillDrawUpdated event broadcasts on private operator channel', function () {
    $event = new PillDrawUpdated(
        eventId: $this->event->id,
        raceClassId: 1,
        driverName: 'Test, Driver',
        carNumber: '42',
        state: 'dancing',
        assignedPill: null,
        drawnPills: [1, 2, 3],
        pillMin: 1,
        pillMax: 50,
        operatorToken: 'abc-123',
    );

    $channels = $event->broadcastOn();

    expect($channels)->toHaveCount(1);
    expect($channels[0])->toBeInstanceOf(PrivateChannel::class);
    expect($channels[0]->name)->toBe("private-event.{$this->event->id}.operator.abc-123");
});
