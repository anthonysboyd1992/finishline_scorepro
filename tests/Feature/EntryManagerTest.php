<?php

use App\Models\Competitor;
use App\Models\Entry;
use App\Models\Event;
use App\Models\RaceClass;
use App\Models\Transponder;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
    $this->event = Event::factory()->create();
    $this->raceClass = RaceClass::factory()->create();
    $this->event->raceClasses()->attach($this->raceClass);
});

it('renders the entry manager component', function () {
    Livewire::test('race-management.entry-manager', [
        'eventId' => $this->event->id,
        'raceClassId' => $this->raceClass->id,
    ])->assertOk();
});

it('shows existing entries in the right panel', function () {
    $competitor = Competitor::factory()->create(['first_name' => 'John', 'last_name' => 'Doe']);
    Entry::factory()->create([
        'event_id' => $this->event->id,
        'race_class_id' => $this->raceClass->id,
        'competitor_id' => $competitor->id,
        'car_number' => '42',
        'driver_name' => 'Doe, John',
    ]);

    Livewire::test('race-management.entry-manager', [
        'eventId' => $this->event->id,
        'raceClassId' => $this->raceClass->id,
    ])
        ->assertSee('42')
        ->assertSee('Doe, John');
});

it('shows all competitors by default on the left panel', function () {
    Competitor::factory()->create(['first_name' => 'Jane', 'last_name' => 'Smith', 'car_number' => '99']);
    Competitor::factory()->create(['first_name' => 'Bob', 'last_name' => 'Jones', 'car_number' => '7']);

    Livewire::test('race-management.entry-manager', [
        'eventId' => $this->event->id,
        'raceClassId' => $this->raceClass->id,
    ])
        ->assertSee('Smith, Jane')
        ->assertSee('Jones, Bob');
});

it('searches for competitors by name', function () {
    Competitor::factory()->create(['first_name' => 'Jane', 'last_name' => 'Smith', 'car_number' => '99']);
    Competitor::factory()->create(['first_name' => 'Bob', 'last_name' => 'Johnson', 'car_number' => '7']);

    Livewire::test('race-management.entry-manager', [
        'eventId' => $this->event->id,
        'raceClassId' => $this->raceClass->id,
    ])
        ->set('search', 'Smith')
        ->assertSee('Smith, Jane')
        ->assertDontSee('Johnson, Bob');
});

it('selects a competitor and shows confirm entry form', function () {
    $competitor = Competitor::factory()->create([
        'first_name' => 'Jane',
        'last_name' => 'Smith',
        'car_number' => '99',
    ]);

    Livewire::test('race-management.entry-manager', [
        'eventId' => $this->event->id,
        'raceClassId' => $this->raceClass->id,
    ])
        ->call('selectCompetitor', $competitor->id)
        ->assertSet('entryCarNumber', '99')
        ->assertSet('selectedCompetitorId', $competitor->id)
        ->assertSet('isConfirmingEntry', true);
});

it('confirms an entry for a selected competitor', function () {
    $competitor = Competitor::factory()->create([
        'first_name' => 'Jane',
        'last_name' => 'Smith',
        'car_number' => '99',
        'city' => 'Lima',
        'state' => 'OH',
    ]);

    Livewire::test('race-management.entry-manager', [
        'eventId' => $this->event->id,
        'raceClassId' => $this->raceClass->id,
    ])
        ->call('selectCompetitor', $competitor->id)
        ->set('entryCarNumber', '99X')
        ->set('entryPillNumber', 5)
        ->call('confirmEntry');

    $this->assertDatabaseHas('entries', [
        'event_id' => $this->event->id,
        'race_class_id' => $this->raceClass->id,
        'competitor_id' => $competitor->id,
        'car_number' => '99X',
        'pill_number' => 5,
        'driver_name' => 'Smith, Jane',
    ]);
});

it('creates a new competitor and flows to confirm entry', function () {
    Livewire::test('race-management.entry-manager', [
        'eventId' => $this->event->id,
        'raceClassId' => $this->raceClass->id,
    ])
        ->call('startCreatingCompetitor')
        ->set('competitorFirstName', 'Alex')
        ->set('competitorLastName', 'Turner')
        ->set('competitorCarNumber', '15')
        ->set('competitorCity', 'Troy')
        ->set('competitorState', 'OH')
        ->call('saveCompetitor');

    $competitor = Competitor::query()->where('last_name', 'Turner')->first();
    expect($competitor)->not->toBeNull()
        ->and($competitor->first_name)->toBe('Alex')
        ->and($competitor->car_number)->toBe('15');
});

it('edits an existing entry', function () {
    $competitor = Competitor::factory()->create();
    $entry = Entry::factory()->create([
        'event_id' => $this->event->id,
        'race_class_id' => $this->raceClass->id,
        'competitor_id' => $competitor->id,
        'car_number' => '42',
        'pill_number' => null,
    ]);

    Livewire::test('race-management.entry-manager', [
        'eventId' => $this->event->id,
        'raceClassId' => $this->raceClass->id,
    ])
        ->call('selectEntry', $entry->id)
        ->call('startEditingEntry')
        ->set('entryCarNumber', '42B')
        ->set('entryPillNumber', 3)
        ->call('updateEntry');

    expect($entry->fresh()->car_number)->toBe('42B')
        ->and($entry->fresh()->pill_number)->toBe(3);
});

it('deletes an entry', function () {
    $entry = Entry::factory()->create([
        'event_id' => $this->event->id,
        'race_class_id' => $this->raceClass->id,
    ]);

    Livewire::test('race-management.entry-manager', [
        'eventId' => $this->event->id,
        'raceClassId' => $this->raceClass->id,
    ])
        ->call('selectEntry', $entry->id)
        ->call('deleteEntry');

    $this->assertDatabaseMissing('entries', ['id' => $entry->id]);
});

it('shows entry count in the right panel header', function () {
    Entry::factory()->count(5)->create([
        'event_id' => $this->event->id,
        'race_class_id' => $this->raceClass->id,
    ]);

    Livewire::test('race-management.entry-manager', [
        'eventId' => $this->event->id,
        'raceClassId' => $this->raceClass->id,
    ])->assertSee('(5)');
});

it('validates required fields when creating competitor', function () {
    Livewire::test('race-management.entry-manager', [
        'eventId' => $this->event->id,
        'raceClassId' => $this->raceClass->id,
    ])
        ->call('startCreatingCompetitor')
        ->set('competitorFirstName', '')
        ->set('competitorLastName', '')
        ->call('saveCompetitor')
        ->assertHasErrors(['competitorFirstName', 'competitorLastName']);
});

it('validates required fields when confirming entry', function () {
    $competitor = Competitor::factory()->create();

    Livewire::test('race-management.entry-manager', [
        'eventId' => $this->event->id,
        'raceClassId' => $this->raceClass->id,
    ])
        ->call('selectCompetitor', $competitor->id)
        ->set('entryCarNumber', '')
        ->call('confirmEntry')
        ->assertHasErrors(['entryCarNumber']);
});

it('deletes an entry by id directly', function () {
    $entry = Entry::factory()->create([
        'event_id' => $this->event->id,
        'race_class_id' => $this->raceClass->id,
    ]);

    Livewire::test('race-management.entry-manager', [
        'eventId' => $this->event->id,
        'raceClassId' => $this->raceClass->id,
    ])
        ->call('deleteEntryById', $entry->id);

    $this->assertDatabaseMissing('entries', ['id' => $entry->id]);
});

it('edits an entry by id directly', function () {
    $competitor = Competitor::factory()->create();
    $entry = Entry::factory()->create([
        'event_id' => $this->event->id,
        'race_class_id' => $this->raceClass->id,
        'competitor_id' => $competitor->id,
        'car_number' => '10',
        'pill_number' => null,
    ]);

    Livewire::test('race-management.entry-manager', [
        'eventId' => $this->event->id,
        'raceClassId' => $this->raceClass->id,
    ])
        ->call('editEntryById', $entry->id)
        ->assertSet('isEditingEntry', true)
        ->assertSet('selectedEntryId', $entry->id)
        ->assertSet('entryCarNumber', '10')
        ->set('entryCarNumber', '10X')
        ->call('updateEntry');

    expect($entry->fresh()->car_number)->toBe('10X');
});

it('keeps search results after confirming an entry', function () {
    $competitor = Competitor::factory()->create([
        'first_name' => 'Jane',
        'last_name' => 'Smith',
        'car_number' => '99',
    ]);

    Livewire::test('race-management.entry-manager', [
        'eventId' => $this->event->id,
        'raceClassId' => $this->raceClass->id,
    ])
        ->set('search', 'Smith')
        ->call('selectCompetitor', $competitor->id)
        ->set('entryCarNumber', '99')
        ->call('confirmEntry')
        ->assertSet('search', 'Smith');
});

it('clears search when clear button is clicked', function () {
    Livewire::test('race-management.entry-manager', [
        'eventId' => $this->event->id,
        'raceClassId' => $this->raceClass->id,
    ])
        ->set('search', 'Smith')
        ->call('clearSearch')
        ->assertSet('search', '');
});

it('saves transponder number with an entry', function () {
    $competitor = Competitor::factory()->create(['car_number' => '42']);

    Livewire::test('race-management.entry-manager', [
        'eventId' => $this->event->id,
        'raceClassId' => $this->raceClass->id,
    ])
        ->call('selectCompetitor', $competitor->id)
        ->set('entryCarNumber', '42')
        ->set('entryTransponder', '1234567890123')
        ->call('confirmEntry');

    $entry = Entry::query()->where('event_id', $this->event->id)->first();
    expect($entry->transponder)->toBe('1234567890123');
});

it('resolves a transponder alias to the full number', function () {
    Transponder::factory()->create([
        'number' => '9876543210987',
        'alias' => 'A01',
    ]);

    $component = Livewire::test('race-management.entry-manager', [
        'eventId' => $this->event->id,
        'raceClassId' => $this->raceClass->id,
    ])
        ->set('entryTransponder', 'A01')
        ->call('resolveTransponder');

    $component->assertSet('entryTransponder', '9876543210987');
    $component->assertSet('transponderResolved', 'A01 → 9876543210987');
});

it('resolves a transponder by number directly', function () {
    Transponder::factory()->create([
        'number' => '5555666677778',
        'alias' => 'B01',
    ]);

    $component = Livewire::test('race-management.entry-manager', [
        'eventId' => $this->event->id,
        'raceClassId' => $this->raceClass->id,
    ])
        ->set('entryTransponder', '5555666677778')
        ->call('resolveTransponder');

    $component->assertSet('entryTransponder', '5555666677778');
    $component->assertSet('transponderResolved', 'B01 → 5555666677778');
});

it('leaves transponder unchanged if alias not found', function () {
    $component = Livewire::test('race-management.entry-manager', [
        'eventId' => $this->event->id,
        'raceClassId' => $this->raceClass->id,
    ])
        ->set('entryTransponder', 'UNKNOWN123')
        ->call('resolveTransponder');

    $component->assertSet('entryTransponder', 'UNKNOWN123');
    $component->assertSet('transponderResolved', '');
});

it('loads pill settings from pivot table', function () {
    $this->event->raceClasses()->updateExistingPivot($this->raceClass->id, [
        'pill_mode' => 'auto_after_confirm',
        'pill_min' => 1,
        'pill_max' => 50,
    ]);

    $component = Livewire::test('race-management.entry-manager', [
        'eventId' => $this->event->id,
        'raceClassId' => $this->raceClass->id,
    ]);

    expect($component->get('pillSettings'))->toBe([
        'enabled' => true,
        'min' => 1,
        'max' => 50,
        'mode' => 'auto_after_confirm',
    ]);
});

it('falls back to class defaults when pivot has no pill settings', function () {
    \App\Models\RaceClassDefault::create([
        'race_class_id' => $this->raceClass->id,
        'pill_mode' => 'manual_on_confirm',
        'pill_min' => 5,
        'pill_max' => 30,
    ]);

    $component = Livewire::test('race-management.entry-manager', [
        'eventId' => $this->event->id,
        'raceClassId' => $this->raceClass->id,
    ]);

    expect($component->get('pillSettings'))->toBe([
        'enabled' => true,
        'min' => 5,
        'max' => 30,
        'mode' => 'manual_on_confirm',
    ]);
});

it('returns pill disabled when no settings exist', function () {
    $component = Livewire::test('race-management.entry-manager', [
        'eventId' => $this->event->id,
        'raceClassId' => $this->raceClass->id,
    ]);

    expect($component->get('pillSettings'))->toBe([
        'enabled' => false,
        'min' => 1,
        'max' => 999,
        'mode' => 'none',
    ]);
});

it('hides pill column header when pill is disabled', function () {
    Entry::factory()->create([
        'event_id' => $this->event->id,
        'race_class_id' => $this->raceClass->id,
        'pill_number' => null,
    ]);

    Livewire::test('race-management.entry-manager', [
        'eventId' => $this->event->id,
        'raceClassId' => $this->raceClass->id,
    ])
        ->assertDontSee('Draw pill number');
});

it('shows per-row draw button when pill is enabled', function () {
    $this->event->raceClasses()->updateExistingPivot($this->raceClass->id, [
        'pill_mode' => 'auto_after_confirm',
        'pill_min' => 1,
        'pill_max' => 20,
    ]);

    Entry::factory()->create([
        'event_id' => $this->event->id,
        'race_class_id' => $this->raceClass->id,
        'pill_number' => null,
    ]);

    Livewire::test('race-management.entry-manager', [
        'eventId' => $this->event->id,
        'raceClassId' => $this->raceClass->id,
    ])
        ->assertSee('Draw');
});

it('assigns a pill number to the current draw entry and stays in draw mode', function () {
    $this->event->raceClasses()->updateExistingPivot($this->raceClass->id, [
        'pill_mode' => 'auto_after_confirm',
        'pill_min' => 1,
        'pill_max' => 20,
    ]);

    $competitor = Competitor::factory()->create();
    $entry = Entry::factory()->create([
        'event_id' => $this->event->id,
        'race_class_id' => $this->raceClass->id,
        'competitor_id' => $competitor->id,
        'pill_number' => null,
        'driver_name' => 'Test, Driver',
    ]);

    $component = Livewire::test('race-management.entry-manager', [
        'eventId' => $this->event->id,
        'raceClassId' => $this->raceClass->id,
    ])
        ->call('drawPill', $entry->id)
        ->call('assignPill', 7);

    expect($entry->fresh()->pill_number)->toBe(7);

    // Stays in draw mode showing the result, does not advance
    $component->assertSet('isDrawing', true)
        ->assertSet('currentDrawEntryId', $entry->id);
});

it('can manually exit pill draw mode', function () {
    $this->event->raceClasses()->updateExistingPivot($this->raceClass->id, [
        'pill_mode' => 'auto_after_confirm',
        'pill_min' => 1,
        'pill_max' => 20,
    ]);

    $competitor = Competitor::factory()->create();
    $entry = Entry::factory()->create([
        'event_id' => $this->event->id,
        'race_class_id' => $this->raceClass->id,
        'competitor_id' => $competitor->id,
        'pill_number' => null,
        'driver_name' => 'Some, Driver',
    ]);

    Livewire::test('race-management.entry-manager', [
        'eventId' => $this->event->id,
        'raceClassId' => $this->raceClass->id,
    ])
        ->call('drawPill', $entry->id)
        ->assertSet('isDrawing', true)
        ->call('exitPillDraw')
        ->assertSet('isDrawing', false)
        ->assertSet('currentDrawEntryId', null);
});

it('drawPill enters draw mode for the given entry', function () {
    $this->event->raceClasses()->updateExistingPivot($this->raceClass->id, [
        'pill_mode' => 'auto_after_confirm',
        'pill_min' => 10,
        'pill_max' => 15,
    ]);

    $competitor = Competitor::factory()->create();
    $entry = Entry::factory()->create([
        'event_id' => $this->event->id,
        'race_class_id' => $this->raceClass->id,
        'competitor_id' => $competitor->id,
        'pill_number' => null,
    ]);

    Livewire::test('race-management.entry-manager', [
        'eventId' => $this->event->id,
        'raceClassId' => $this->raceClass->id,
    ])
        ->call('drawPill', $entry->id)
        ->assertSet('isDrawing', true)
        ->assertSet('currentDrawEntryId', $entry->id);
});

it('dispatches PillDrawUpdated event when drawPill is called', function () {
    \Illuminate\Support\Facades\Event::fake([\App\Events\PillDrawUpdated::class]);

    $this->event->raceClasses()->updateExistingPivot($this->raceClass->id, [
        'pill_mode' => 'auto_after_confirm',
        'pill_min' => 1,
        'pill_max' => 20,
    ]);

    $competitor = Competitor::factory()->create();
    $entry = Entry::factory()->create([
        'event_id' => $this->event->id,
        'race_class_id' => $this->raceClass->id,
        'competitor_id' => $competitor->id,
        'pill_number' => null,
        'driver_name' => 'Broadcast, Test',
    ]);

    Livewire::test('race-management.entry-manager', [
        'eventId' => $this->event->id,
        'raceClassId' => $this->raceClass->id,
    ])
        ->call('drawPill', $entry->id);

    \Illuminate\Support\Facades\Event::assertDispatched(\App\Events\PillDrawUpdated::class, function ($e) {
        return $e->state === 'idle'
            && $e->eventId === $this->event->id
            && $e->driverName === 'Broadcast, Test';
    });
});

it('dispatches PillDrawUpdated event when pill is assigned', function () {
    \Illuminate\Support\Facades\Event::fake([\App\Events\PillDrawUpdated::class]);

    $this->event->raceClasses()->updateExistingPivot($this->raceClass->id, [
        'pill_mode' => 'auto_after_confirm',
        'pill_min' => 1,
        'pill_max' => 20,
    ]);

    $competitor = Competitor::factory()->create();
    $entry = Entry::factory()->create([
        'event_id' => $this->event->id,
        'race_class_id' => $this->raceClass->id,
        'competitor_id' => $competitor->id,
        'pill_number' => null,
        'driver_name' => 'Assign, Test',
    ]);

    Livewire::test('race-management.entry-manager', [
        'eventId' => $this->event->id,
        'raceClassId' => $this->raceClass->id,
    ])
        ->call('drawPill', $entry->id)
        ->call('assignPill', 5);

    \Illuminate\Support\Facades\Event::assertDispatched(\App\Events\PillDrawUpdated::class, function ($e) {
        return $e->state === 'stopped' && $e->assignedPill === 5;
    });
});

it('dispatches PillDrawUpdated idle event when exiting draw', function () {
    \Illuminate\Support\Facades\Event::fake([\App\Events\PillDrawUpdated::class]);

    $this->event->raceClasses()->updateExistingPivot($this->raceClass->id, [
        'pill_mode' => 'auto_after_confirm',
        'pill_min' => 1,
        'pill_max' => 20,
    ]);

    $competitor = Competitor::factory()->create();
    $entry = Entry::factory()->create([
        'event_id' => $this->event->id,
        'race_class_id' => $this->raceClass->id,
        'competitor_id' => $competitor->id,
        'pill_number' => null,
    ]);

    Livewire::test('race-management.entry-manager', [
        'eventId' => $this->event->id,
        'raceClassId' => $this->raceClass->id,
    ])
        ->call('drawPill', $entry->id)
        ->call('exitPillDraw');

    \Illuminate\Support\Facades\Event::assertDispatched(\App\Events\PillDrawUpdated::class, function ($e) {
        return $e->state === 'idle' && $e->driverName === '';
    });
});
