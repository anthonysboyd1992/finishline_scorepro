<?php

use App\LineupScripts\HeatsByGroup;
use App\LineupScripts\Invert;
use App\LineupScripts\LineupStrategy;
use App\LineupScripts\PillDraw;
use App\LineupScripts\PointsStanding;
use App\LineupScripts\QualifyingResults;
use App\LineupScripts\QualifyingTime;
use App\LineupScripts\Random;

$entries = [
    ['id' => 1, 'number' => '24', 'driver' => 'Allison, Kobe', 'city' => 'Lima, OH'],
    ['id' => 2, 'number' => '5', 'driver' => 'Christie, D.J.', 'city' => 'Beachville, ON'],
    ['id' => 3, 'number' => '6', 'driver' => 'Coniam, Ryan', 'city' => 'Burlington, ON'],
    ['id' => 4, 'number' => '85', 'driver' => 'Daggett, Dustin', 'city' => 'Portland, MI'],
    ['id' => 5, 'number' => '23', 'driver' => 'Dobie, Devon', 'city' => 'Wapakoneta, OH'],
];

beforeEach(function () use ($entries) {
    $this->entries = $entries;
});

it('implements the LineupStrategy interface', function (string $class) {
    $strategy = new $class;
    expect($strategy)->toBeInstanceOf(LineupStrategy::class);
})->with([
    HeatsByGroup::class,
    PillDraw::class,
    QualifyingResults::class,
    QualifyingTime::class,
    PointsStanding::class,
    Invert::class,
    Random::class,
]);

it('heats by group returns entries in original order', function () {
    $result = (new HeatsByGroup)->order($this->entries);

    expect($result)->toBe($this->entries);
});

it('invert reverses the entry order', function () {
    $result = (new Invert)->order($this->entries);

    expect($result)->toBe(array_reverse($this->entries));
    expect($result[0]['id'])->toBe(5);
    expect($result[4]['id'])->toBe(1);
});

it('qualifying time sorts by car number naturally', function () {
    $result = (new QualifyingTime)->order($this->entries);

    $numbers = array_column($result, 'number');
    expect($numbers)->toBe(['5', '6', '23', '24', '85']);
});

it('points standing sorts by driver name alphabetically', function () {
    $result = (new PointsStanding)->order($this->entries);

    $drivers = array_column($result, 'driver');
    expect($drivers)->toBe([
        'Allison, Kobe',
        'Christie, D.J.',
        'Coniam, Ryan',
        'Daggett, Dustin',
        'Dobie, Devon',
    ]);
});

it('pill draw shuffles entries', function () {
    $result = (new PillDraw)->order($this->entries);

    expect($result)->toHaveCount(5);
    // All original entries are present
    $ids = array_column($result, 'id');
    sort($ids);
    expect($ids)->toBe([1, 2, 3, 4, 5]);
});

it('random shuffles entries', function () {
    $result = (new Random)->order($this->entries);

    expect($result)->toHaveCount(5);
    $ids = array_column($result, 'id');
    sort($ids);
    expect($ids)->toBe([1, 2, 3, 4, 5]);
});

// Note: QualifyingResults is excluded from unit tests because it queries the DB.
// It is fully tested in Feature/QualifyingResultsStrategyTest.

it('all strategies handle an empty array', function (string $class) {
    $result = (new $class)->order([]);
    expect($result)->toBe([]);
})->with([
    HeatsByGroup::class,
    PillDraw::class,
    QualifyingTime::class,
    PointsStanding::class,
    Invert::class,
    Random::class,
]);

it('all strategies accept a config parameter', function (string $class) {
    $result = (new $class)->order($this->entries, ['custom' => 'value']);
    expect($result)->toHaveCount(5);
})->with([
    HeatsByGroup::class,
    PillDraw::class,
    QualifyingTime::class,
    PointsStanding::class,
    Invert::class,
    Random::class,
]);
