<?php

use App\LineupScripts\QualifyingResults;
use App\Models\Entry;
use App\Models\Event;
use App\Models\RaceClass;
use App\Models\RaceResult;
use App\Models\Track;

beforeEach(function () {
    $track = Track::factory()->create();
    $this->event = Event::factory()->for($track)->create();
    $this->raceClass = RaceClass::factory()->create();

    $this->entries = collect();
    foreach (['Fast Driver', 'Medium Driver', 'Slow Driver', 'No Time Driver'] as $i => $name) {
        $this->entries->push(Entry::factory()->create([
            'event_id' => $this->event->id,
            'race_class_id' => $this->raceClass->id,
            'car_number' => (string) ($i + 1),
            'driver_name' => $name,
        ]));
    }
});

it('sorts entries by fastest qualifying time from the database', function () {
    $fast = $this->entries[0];
    $medium = $this->entries[1];
    $slow = $this->entries[2];
    // entries[3] has no qualifying result

    RaceResult::factory()->qualifying()->create(['entry_id' => $fast->id, 'best_lap_time' => 14.100]);
    RaceResult::factory()->qualifying()->create(['entry_id' => $medium->id, 'best_lap_time' => 15.500]);
    RaceResult::factory()->qualifying()->create(['entry_id' => $slow->id, 'best_lap_time' => 17.200]);

    $entryArrays = $this->entries->map(fn (Entry $e) => [
        'id' => $e->id,
        'number' => $e->car_number,
        'driver' => $e->driver_name,
        'city' => $e->city ?? '',
    ])->all();

    $result = (new QualifyingResults)->order($entryArrays);

    $ids = array_column($result, 'id');
    expect($ids[0])->toBe($fast->id)
        ->and($ids[1])->toBe($medium->id)
        ->and($ids[2])->toBe($slow->id)
        ->and($ids[3])->toBe($this->entries[3]->id);
});

it('places entries without qualifying results at the end', function () {
    $withTime = $this->entries[0];
    $noTime = $this->entries[1];

    RaceResult::factory()->qualifying()->create(['entry_id' => $withTime->id, 'best_lap_time' => 15.000]);

    $entryArrays = $this->entries->take(2)->map(fn (Entry $e) => [
        'id' => $e->id,
        'number' => $e->car_number,
        'driver' => $e->driver_name,
        'city' => $e->city ?? '',
    ])->all();

    $result = (new QualifyingResults)->order($entryArrays);

    expect($result[0]['id'])->toBe($withTime->id)
        ->and($result[1]['id'])->toBe($noTime->id);
});

it('uses the fastest qualifying time when multiple results exist', function () {
    $entry = $this->entries[0];

    // Two qualifying sessions, second one is faster
    RaceResult::factory()->qualifying(1)->create(['entry_id' => $entry->id, 'best_lap_time' => 16.000]);
    RaceResult::factory()->qualifying(2)->create(['entry_id' => $entry->id, 'best_lap_time' => 14.500]);

    $otherEntry = $this->entries[1];
    RaceResult::factory()->qualifying()->create(['entry_id' => $otherEntry->id, 'best_lap_time' => 15.000]);

    $entryArrays = $this->entries->take(2)->map(fn (Entry $e) => [
        'id' => $e->id,
        'number' => $e->car_number,
        'driver' => $e->driver_name,
        'city' => $e->city ?? '',
    ])->all();

    $result = (new QualifyingResults)->order($entryArrays);

    // Entry with 14.5 (best of two) should be first
    expect($result[0]['id'])->toBe($entry->id)
        ->and($result[1]['id'])->toBe($otherEntry->id);
});

it('ignores non-qualifying race results', function () {
    $entry = $this->entries[0];

    // Only a heat result, no qualifying result
    RaceResult::factory()->create([
        'entry_id' => $entry->id,
        'race_type' => 'Heat',
        'best_lap_time' => 14.000,
    ]);

    $entryArrays = $this->entries->take(1)->map(fn (Entry $e) => [
        'id' => $e->id,
        'number' => $e->car_number,
        'driver' => $e->driver_name,
        'city' => $e->city ?? '',
    ])->all();

    $result = (new QualifyingResults)->order($entryArrays);

    // Still returns the entry (just no qualifying time to sort by)
    expect($result)->toHaveCount(1)
        ->and($result[0]['id'])->toBe($entry->id);
});

it('handles an empty entry list', function () {
    $result = (new QualifyingResults)->order([]);
    expect($result)->toBe([]);
});
