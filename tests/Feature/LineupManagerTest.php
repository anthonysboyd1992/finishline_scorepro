<?php

use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('renders the lineups page', function () {
    $this->actingAs($this->user)
        ->get(route('race-management.lineups'))
        ->assertStatus(200)
        ->assertSee('Lineups')
        ->assertSee('Calculate Lineups');
});

it('mounts with unassigned entries and empty heats', function () {
    $component = Livewire::actingAs($this->user)
        ->test('race-management.lineup-manager')
        ->assertCount('unassignedEntries', 32)
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
    Livewire::actingAs($this->user)
        ->test('race-management.lineup-manager')
        ->call('calculateLineups')
        ->assertCount('unassignedEntries', 0)
        ->assertSee('Heat 1')
        ->assertSee('Heat 2');
});

it('clears lineups and returns entries to unassigned list with empty heats', function () {
    $component = Livewire::actingAs($this->user)
        ->test('race-management.lineup-manager')
        ->call('calculateLineups')
        ->assertCount('unassignedEntries', 0)
        ->call('clearLineups')
        ->assertCount('unassignedEntries', 32);

    $heats = $component->get('heats');
    expect($heats)->toHaveCount(4);

    foreach ($heats as $heat) {
        expect($heat['entries'])->toBeEmpty();
    }
});

it('filters entries by driver name', function () {
    Livewire::actingAs($this->user)
        ->test('race-management.lineup-manager')
        ->set('filter', 'Turner')
        ->assertSee('Turner, Ryan')
        ->assertDontSee('Allison, Kobe');
});

it('filters entries by car number', function () {
    Livewire::actingAs($this->user)
        ->test('race-management.lineup-manager')
        ->set('filter', '51X')
        ->assertSee('Hickle, J.J.')
        ->assertDontSee('Turner, Ryan');
});

it('handles sort within entry list', function () {
    Livewire::actingAs($this->user)
        ->test('race-management.lineup-manager')
        ->call('sortEntryList', 1, 5)
        ->assertCount('unassignedEntries', 32);
});

it('removes entry from heat back to unassigned list', function () {
    $component = Livewire::actingAs($this->user)
        ->test('race-management.lineup-manager')
        ->call('calculateLineups')
        ->assertCount('unassignedEntries', 0);

    // Remove entry with id 1 from the first heat (index 0)
    $component->call('removeFromHeat', 0, 1)
        ->assertCount('unassignedEntries', 1)
        ->assertSee('Allison, Kobe');
});

it('drops entry from entry list into a heat', function () {
    Livewire::actingAs($this->user)
        ->test('race-management.lineup-manager')
        ->call('calculateLineups')
        ->assertCount('unassignedEntries', 0)
        ->call('removeFromHeat', 0, 1) // move entry 1 back to unassigned
        ->assertCount('unassignedEntries', 1)
        ->call('dropEntry', 1, '0') // drop entry 1 into heat 0
        ->assertCount('unassignedEntries', 0);
});

it('drops entry from heat to entry list', function () {
    Livewire::actingAs($this->user)
        ->test('race-management.lineup-manager')
        ->call('calculateLineups')
        ->assertCount('unassignedEntries', 0)
        ->call('dropEntry', 1, 'entrylist')
        ->assertCount('unassignedEntries', 1);
});

it('drops entry from one heat to another and grows the heat', function () {
    $component = Livewire::actingAs($this->user)
        ->test('race-management.lineup-manager')
        ->call('calculateLineups');

    $heats = $component->get('heats');
    $firstHeatCount = count($heats[0]['entries']);
    $secondHeatCount = count($heats[1]['entries']);

    // Move entry 1 (in heat 0) to heat 1 - heat 1 grows
    $component->call('dropEntry', 1, '1');

    $heats = $component->get('heats');
    expect(count($heats[0]['entries']))->toBe($firstHeatCount - 1);
    expect(count($heats[1]['entries']))->toBe($secondHeatCount + 1);
});

it('drops entry at an exact position within a heat', function () {
    $component = Livewire::actingAs($this->user)
        ->test('race-management.lineup-manager')
        ->call('calculateLineups');

    // Move entry 1 back to unassigned, then drop it at position 2 in heat 0
    $component->call('dropEntry', 1, 'entrylist')
        ->call('dropEntry', 1, '0', 2);

    $heats = $component->get('heats');
    expect($heats[0]['entries'][2]['id'])->toBe(1);
});

it('adds an empty heat', function () {
    $component = Livewire::actingAs($this->user)
        ->test('race-management.lineup-manager')
        ->call('addHeat');

    $heats = $component->get('heats');
    expect($heats)->toHaveCount(5);
    expect($heats[4]['name'])->toBe('Heat 5');
    expect($heats[4]['entries'])->toBeEmpty();
});

it('removes the last heat and returns its entries to unassigned', function () {
    $component = Livewire::actingAs($this->user)
        ->test('race-management.lineup-manager')
        ->call('calculateLineups');

    $lastHeat = last($component->get('heats'));
    $entriesInLast = count($lastHeat['entries']);
    $heatsBefore = count($component->get('heats'));

    $component->call('removeHeat');

    expect($component->get('heats'))->toHaveCount($heatsBefore - 1);
    expect($component->get('unassignedEntries'))->toHaveCount($entriesInLast);
});

it('does not remove the last remaining heat', function () {
    Livewire::actingAs($this->user)
        ->test('race-management.lineup-manager')
        ->call('removeHeat') // 4 -> 3
        ->call('removeHeat') // 3 -> 2
        ->call('removeHeat') // 2 -> 1
        ->call('removeHeat') // should stay at 1
        ->assertCount('heats', 1);
});

it('drops entry directly into an empty heat', function () {
    $component = Livewire::actingAs($this->user)
        ->test('race-management.lineup-manager')
        ->call('dropEntry', 1, '0', 0);

    expect($component->get('heats')[0]['entries'])->toHaveCount(1);
    expect($component->get('heats')[0]['entries'][0]['id'])->toBe(1);
    expect($component->get('unassignedEntries'))->toHaveCount(31);
});

it('grows a heat when dropping into a full heat instead of bumping', function () {
    $component = Livewire::actingAs($this->user)
        ->test('race-management.lineup-manager')
        ->call('calculateLineups');

    $heats = $component->get('heats');
    $originalCount = count($heats[0]['entries']); // 10

    // Drop an entry from heat 1 into the already-full heat 0
    $heat1Entry = $heats[1]['entries'][0];
    $component->call('dropEntry', $heat1Entry['id'], '0');

    // Heat 0 should now have 11 entries (grew by one)
    expect($component->get('heats')[0]['entries'])->toHaveCount($originalCount + 1);
    expect($component->get('unassignedEntries'))->toHaveCount(0);
});

it('sets the active heat', function () {
    Livewire::actingAs($this->user)
        ->test('race-management.lineup-manager')
        ->assertSet('activeHeat', 0)
        ->call('calculateLineups')
        ->call('setActiveHeat', 2)
        ->assertSet('activeHeat', 2);
});

it('adds entry to active heat when clicked in the entry list', function () {
    $component = Livewire::actingAs($this->user)
        ->test('race-management.lineup-manager')
        ->call('calculateLineups')
        ->call('setActiveHeat', 1);

    $heat1Count = count($component->get('heats')[1]['entries']);

    // Remove entry 1 from heat 0 to unassigned, then click to add to active heat (1)
    $component->call('dropEntry', 1, 'entrylist')
        ->assertCount('unassignedEntries', 1)
        ->call('addEntryToActiveHeat', 1)
        ->assertCount('unassignedEntries', 0);

    expect($component->get('heats')[1]['entries'])->toHaveCount($heat1Count + 1);
});
