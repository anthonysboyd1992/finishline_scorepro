<?php

use App\Models\Entry;
use App\Models\Event;
use App\Models\LineupScript;
use App\Models\RaceClass;
use App\Models\RaceResult;
use App\Models\Sanction;
use App\Models\Track;
use App\Models\User;
use Database\Seeders\EntrySeeder;
use Database\Seeders\LineupScriptSeeder;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->seed(LineupScriptSeeder::class);

    $this->track = Track::factory()->create();
    $this->sanction = Sanction::factory()->create();
    $this->event = Event::factory()->for($this->track)->create();
    $this->raceClass = RaceClass::factory()->create();
    $this->event->sanctions()->attach($this->sanction);
    $this->event->raceClasses()->attach($this->raceClass);
    session(['current_sanction_id' => $this->sanction->id]);

    // Seed entries for this event/class
    $this->seed(EntrySeeder::class);
    $this->entries = Entry::query()
        ->where('event_id', $this->event->id)
        ->where('race_class_id', $this->raceClass->id)
        ->orderBy('driver_name')
        ->get();
    $this->entryCount = $this->entries->count();
    $this->firstEntry = $this->entries->first();
});

/**
 * Helper to get a script ID by slug and race type.
 */
function scriptId(string $slug, string $raceType = 'Heat'): int
{
    return LineupScript::query()
        ->where('slug', $slug)
        ->where('race_type', $raceType)
        ->value('id');
}

/**
 * Helper to create a Livewire test with the required props.
 */
function lineupTest(): \Livewire\Features\SupportTesting\Testable
{
    return Livewire::actingAs(test()->user)
        ->test('race-management.lineup-manager', [
            'eventId' => test()->event->id,
            'raceClassId' => test()->raceClass->id,
        ]);
}

it('renders the lineups page', function () {
    $this->actingAs($this->user)
        ->get(route('race-management.lineups', [
            'event' => $this->event,
            'raceClass' => $this->raceClass,
            'raceType' => 'heat',
        ]))
        ->assertStatus(200)
        ->assertSee('Lineups')
        ->assertSee('Calculate Lineups');
});

it('mounts with unassigned entries and empty heats', function () {
    $component = lineupTest()
        ->assertCount('unassignedEntries', $this->entryCount)
        ->assertSee('Allison, Kobe')
        ->assertSee('Turner, Ryan')
        ->assertSee('Heat 1')
        ->assertSee('Heat 4');

    $heats = $component->get('heats');
    expect($heats)->toHaveCount(4);

    foreach ($heats as $heat) {
        expect($heat['entries'])->toBeEmpty();
    }
});

it('calculates lineups distributing entries into heats', function () {
    lineupTest()
        ->call('selectScript', scriptId('heats_by_group'))
        ->assertCount('unassignedEntries', 0)
        ->assertSet('showScriptPicker', false)
        ->assertSet('lineupMethod', 'Heats By Group')
        ->assertSee('Heat 1')
        ->assertSee('Heat 2');
});

it('clears lineups and returns entries to unassigned list with empty heats', function () {
    $component = lineupTest()
        ->call('selectScript', scriptId('heats_by_group'))
        ->assertCount('unassignedEntries', 0)
        ->call('clearLineups')
        ->assertCount('unassignedEntries', $this->entryCount);

    $heats = $component->get('heats');
    expect($heats)->toHaveCount(4);

    foreach ($heats as $heat) {
        expect($heat['entries'])->toBeEmpty();
    }
});

it('filters entries by driver name', function () {
    lineupTest()
        ->set('filter', 'Turner')
        ->assertSee('Turner, Ryan')
        ->assertDontSee('Allison, Kobe');
});

it('filters entries by car number', function () {
    lineupTest()
        ->set('filter', '51X')
        ->assertSee('Hickle, J.J.')
        ->assertDontSee('Turner, Ryan');
});

it('handles sort within entry list', function () {
    lineupTest()
        ->call('sortEntryList', 1, 5)
        ->assertCount('unassignedEntries', $this->entryCount);
});

it('removes entry from heat back to unassigned list', function () {
    $entryId = $this->firstEntry->id;

    $component = lineupTest()
        ->call('selectScript', scriptId('heats_by_group'))
        ->assertCount('unassignedEntries', 0);

    $component->call('removeFromHeat', 0, $entryId)
        ->assertCount('unassignedEntries', 1);
});

it('drops entry from entry list into a heat', function () {
    $entryId = $this->firstEntry->id;

    lineupTest()
        ->call('selectScript', scriptId('heats_by_group'))
        ->assertCount('unassignedEntries', 0)
        ->call('removeFromHeat', 0, $entryId)
        ->assertCount('unassignedEntries', 1)
        ->call('dropEntry', $entryId, '0')
        ->assertCount('unassignedEntries', 0);
});

it('drops entry from heat to entry list', function () {
    $entryId = $this->firstEntry->id;

    lineupTest()
        ->call('selectScript', scriptId('heats_by_group'))
        ->assertCount('unassignedEntries', 0)
        ->call('dropEntry', $entryId, 'entrylist')
        ->assertCount('unassignedEntries', 1);
});

it('drops entry from one heat to another and grows the heat', function () {
    $component = lineupTest()
        ->call('selectScript', scriptId('heats_by_group'));

    $heats = $component->get('heats');
    $firstHeatCount = count($heats[0]['entries']);
    $secondHeatCount = count($heats[1]['entries']);

    $firstHeatEntry = $heats[0]['entries'][0];
    $component->call('dropEntry', $firstHeatEntry['id'], '1');

    $heats = $component->get('heats');
    expect(count($heats[0]['entries']))->toBe($firstHeatCount - 1);
    expect(count($heats[1]['entries']))->toBe($secondHeatCount + 1);
});

it('drops entry at an exact position within a heat', function () {
    $entryId = $this->firstEntry->id;

    $component = lineupTest()
        ->call('selectScript', scriptId('heats_by_group'));

    $component->call('dropEntry', $entryId, 'entrylist')
        ->call('dropEntry', $entryId, '0', 2);

    $heats = $component->get('heats');
    expect($heats[0]['entries'][2]['id'])->toBe($entryId);
});

it('adds an empty heat', function () {
    $component = lineupTest()
        ->call('addHeat');

    $heats = $component->get('heats');
    expect($heats)->toHaveCount(5);
    expect($heats[4]['name'])->toBe('Heat 5');
    expect($heats[4]['entries'])->toBeEmpty();
});

it('removes the last heat and returns its entries to unassigned', function () {
    $component = lineupTest()
        ->call('selectScript', scriptId('heats_by_group'));

    $lastHeat = last($component->get('heats'));
    $entriesInLast = count($lastHeat['entries']);
    $heatsBefore = count($component->get('heats'));

    $component->call('removeHeat');

    expect($component->get('heats'))->toHaveCount($heatsBefore - 1);
    expect($component->get('unassignedEntries'))->toHaveCount($entriesInLast);
});

it('does not remove the last remaining heat', function () {
    lineupTest()
        ->call('removeHeat') // 4 -> 3
        ->call('removeHeat') // 3 -> 2
        ->call('removeHeat') // 2 -> 1
        ->call('removeHeat') // should stay at 1
        ->assertCount('heats', 1);
});

it('drops entry directly into an empty heat', function () {
    $entryId = $this->firstEntry->id;

    $component = lineupTest()
        ->call('dropEntry', $entryId, '0', 0);

    expect($component->get('heats')[0]['entries'])->toHaveCount(1);
    expect($component->get('heats')[0]['entries'][0]['id'])->toBe($entryId);
    expect($component->get('unassignedEntries'))->toHaveCount($this->entryCount - 1);
});

it('grows a heat when dropping into a full heat instead of bumping', function () {
    $component = lineupTest()
        ->call('selectScript', scriptId('heats_by_group'));

    $heats = $component->get('heats');
    $originalCount = count($heats[0]['entries']);

    $heat1Entry = $heats[1]['entries'][0];
    $component->call('dropEntry', $heat1Entry['id'], '0');

    expect($component->get('heats')[0]['entries'])->toHaveCount($originalCount + 1);
    expect($component->get('unassignedEntries'))->toHaveCount(0);
});

it('sets the active heat', function () {
    lineupTest()
        ->assertSet('activeHeat', 0)
        ->call('selectScript', scriptId('heats_by_group'))
        ->call('setActiveHeat', 2)
        ->assertSet('activeHeat', 2);
});

it('adds entry to active heat when clicked in the entry list', function () {
    $entryId = $this->firstEntry->id;

    $component = lineupTest()
        ->call('selectScript', scriptId('heats_by_group'))
        ->call('setActiveHeat', 1);

    $heat1Count = count($component->get('heats')[1]['entries']);

    $component->call('dropEntry', $entryId, 'entrylist')
        ->assertCount('unassignedEntries', 1)
        ->call('addEntryToActiveHeat', $entryId)
        ->assertCount('unassignedEntries', 0);

    expect($component->get('heats')[1]['entries'])->toHaveCount($heat1Count + 1);
});

it('shows the script picker filtered to the current race type', function () {
    lineupTest()
        ->assertSet('showScriptPicker', false)
        ->call('showScripts')
        ->assertSet('showScriptPicker', true)
        ->assertSee('Select Lineup Script')
        ->assertSee('Heat Scripts')
        ->assertSee('Heats By Group')
        ->assertSee('Pill Draw')
        ->assertSee('Random');
});

it('shows only scripts matching the selected race type', function () {
    Livewire::actingAs($this->user)
        ->test('race-management.lineup-manager', [
            'raceTypeLabel' => 'Qualifying',
            'eventId' => $this->event->id,
            'raceClassId' => $this->raceClass->id,
        ])
        ->call('showScripts')
        ->assertSet('showScriptPicker', true)
        ->assertSee('Qualifying Scripts')
        ->assertSee('Random')
        ->assertSee('Pill Draw')
        ->assertSee('Points Standing')
        ->assertDontSee('Heats By Group')
        ->assertDontSee('Heat Scripts');
});

it('cancels the script picker and returns to heat cards', function () {
    $component = lineupTest()
        ->call('showScripts')
        ->assertSet('showScriptPicker', true)
        ->call('cancelScriptPicker')
        ->assertSet('showScriptPicker', false)
        ->assertDontSee('Select Lineup Script');

    expect($component->get('heats'))->toHaveCount(4);
    expect($component->get('unassignedEntries'))->toHaveCount($this->entryCount);
});

it('selects a script by id and calculates lineups', function () {
    lineupTest()
        ->call('showScripts')
        ->assertSet('showScriptPicker', true)
        ->call('selectScript', scriptId('heats_by_group'))
        ->assertSet('showScriptPicker', false)
        ->assertSet('lineupMethod', 'Heats By Group')
        ->assertCount('unassignedEntries', 0);
});

it('selects the random script and distributes entries', function () {
    $component = lineupTest()
        ->call('selectScript', scriptId('random'))
        ->assertSet('showScriptPicker', false)
        ->assertSet('lineupMethod', 'Random')
        ->assertCount('unassignedEntries', 0);

    $totalInHeats = 0;
    foreach ($component->get('heats') as $heat) {
        $totalInHeats += count($heat['entries']);
    }
    expect($totalInHeats)->toBe($this->entryCount);
});

it('ignores an invalid script id', function () {
    lineupTest()
        ->call('selectScript', 99999)
        ->assertSet('showScriptPicker', false)
        ->assertSet('lineupMethod', '')
        ->assertCount('unassignedEntries', $this->entryCount);
});

it('defaults to Heat as the race type label', function () {
    lineupTest()
        ->assertSet('raceTypeLabel', 'Heat')
        ->assertSee('Heat 1')
        ->assertSee('Heat 4');
});

it('mounts with a custom race type label', function () {
    Livewire::actingAs($this->user)
        ->test('race-management.lineup-manager', [
            'raceTypeLabel' => 'Hotlaps',
            'eventId' => $this->event->id,
            'raceClassId' => $this->raceClass->id,
        ])
        ->assertSet('raceTypeLabel', 'Hotlaps')
        ->assertSee('Hotlaps 1')
        ->assertSee('Hotlaps 4')
        ->assertDontSee('Heat 1');
});

it('renames heats when race-type-selected event is dispatched', function () {
    $component = lineupTest()
        ->assertSee('Heat 1')
        ->dispatch('race-type-selected', label: 'Qualifying')
        ->assertSet('raceTypeLabel', 'Qualifying')
        ->assertSee('Qualifying 1')
        ->assertSee('Qualifying 4')
        ->assertDontSee('Heat 1');

    $heats = $component->get('heats');
    expect($heats[0]['name'])->toBe('Qualifying 1');
    expect($heats[3]['name'])->toBe('Qualifying 4');
});

it('uses the current race type label when adding a heat', function () {
    $component = Livewire::actingAs($this->user)
        ->test('race-management.lineup-manager', [
            'raceTypeLabel' => 'Dash',
            'eventId' => $this->event->id,
            'raceClassId' => $this->raceClass->id,
        ])
        ->call('addHeat');

    $heats = $component->get('heats');
    expect($heats[4]['name'])->toBe('Dash 5');
});

it('uses the current race type label when calculating lineups', function () {
    $component = Livewire::actingAs($this->user)
        ->test('race-management.lineup-manager', [
            'raceTypeLabel' => 'A-Feature',
            'eventId' => $this->event->id,
            'raceClassId' => $this->raceClass->id,
        ])
        ->call('selectScript', scriptId('heats_by_group'))
        ->assertSee('A-Feature 1');

    $heats = $component->get('heats');
    expect($heats[0]['name'])->toBe('A-Feature 1');
});

it('sorts entries by qualifying results when using qualifying results script', function () {
    // Create qualifying results with known times
    $fastEntry = $this->entries->firstWhere('driver_name', 'Turner, Ryan');
    $slowEntry = $this->entries->firstWhere('driver_name', 'Allison, Kobe');

    RaceResult::factory()->qualifying()->create([
        'entry_id' => $fastEntry->id,
        'best_lap_time' => 14.100,
    ]);
    RaceResult::factory()->qualifying()->create([
        'entry_id' => $slowEntry->id,
        'best_lap_time' => 18.500,
    ]);

    $component = lineupTest()
        ->call('selectScript', scriptId('qualifying_results'));

    // All entries should be distributed
    $component->assertCount('unassignedEntries', 0);

    // The first entry in the first heat should be Turner (fastest)
    $heats = $component->get('heats');
    $firstHeatFirst = $heats[0]['entries'][0];
    expect($firstHeatFirst['id'])->toBe($fastEntry->id);
});
