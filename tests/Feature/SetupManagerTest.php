<?php

use App\Models\Event;
use App\Models\RaceClass;
use App\Models\RaceClassDefault;
use App\Models\Sanction;
use App\Models\Track;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
    $this->track = Track::factory()->create();
    $this->sanction = Sanction::factory()->create();
    $this->event = Event::factory()->for($this->track)->create();
    $this->raceClass = RaceClass::factory()->create();

    $this->event->sanctions()->attach($this->sanction);
    $this->event->raceClasses()->attach($this->raceClass);

    session(['current_sanction_id' => $this->sanction->id]);
});

it('renders the setup manager component', function () {
    Livewire::test('race-management.setup-manager', [
        'eventId' => $this->event->id,
        'raceClassId' => $this->raceClass->id,
    ])->assertOk();
});

it('shows the no-class state when raceClassId is 0', function () {
    Livewire::test('race-management.setup-manager', [
        'eventId' => $this->event->id,
        'raceClassId' => 0,
    ])
        ->assertOk()
        ->assertSee('No classes for this event');
});

it('shows pill options by default', function () {
    Livewire::test('race-management.setup-manager', [
        'eventId' => $this->event->id,
        'raceClassId' => $this->raceClass->id,
    ])
        ->assertSee('Pill Options')
        ->assertSee('No Pill Draw');
});

it('switches to transponder options category', function () {
    Livewire::test('race-management.setup-manager', [
        'eventId' => $this->event->id,
        'raceClassId' => $this->raceClass->id,
    ])
        ->call('setCategory', 'transponder')
        ->assertSee('Transponder Options')
        ->assertSee('No Transponders');
});

it('switches to race options category', function () {
    Livewire::test('race-management.setup-manager', [
        'eventId' => $this->event->id,
        'raceClassId' => $this->raceClass->id,
    ])
        ->call('setCategory', 'race')
        ->assertSee('Race Options')
        ->assertSee('Default Competitors Per Row');
});

it('loads hardcoded defaults when no settings exist', function () {
    Livewire::test('race-management.setup-manager', [
        'eventId' => $this->event->id,
        'raceClassId' => $this->raceClass->id,
    ])
        ->assertSet('pillMode', 'none')
        ->assertSet('pillMax', 500)
        ->assertSet('pillMin', 1)
        ->assertSet('transponderMode', 'none')
        ->assertSet('competitorsPerRow', 2);
});

it('loads settings from class defaults when no pivot settings exist', function () {
    RaceClassDefault::create([
        'race_class_id' => $this->raceClass->id,
        'pill_mode' => 'auto_after_confirm',
        'pill_max' => 500,
        'pill_min' => 10,
        'transponder_mode' => 'allow_rentals',
        'competitors_per_row' => 3,
    ]);

    Livewire::test('race-management.setup-manager', [
        'eventId' => $this->event->id,
        'raceClassId' => $this->raceClass->id,
    ])
        ->assertSet('pillMode', 'auto_after_confirm')
        ->assertSet('pillMax', 500)
        ->assertSet('pillMin', 10)
        ->assertSet('transponderMode', 'allow_rentals')
        ->assertSet('competitorsPerRow', 3);
});

it('loads settings from pivot when they exist', function () {
    $this->event->raceClasses()->syncWithoutDetaching([
        $this->raceClass->id => [
            'pill_mode' => 'manual_on_confirm',
            'pill_max' => 200,
            'pill_min' => 5,
            'transponder_mode' => 'no_rentals',
            'competitors_per_row' => 4,
        ],
    ]);

    Livewire::test('race-management.setup-manager', [
        'eventId' => $this->event->id,
        'raceClassId' => $this->raceClass->id,
    ])
        ->assertSet('pillMode', 'manual_on_confirm')
        ->assertSet('pillMax', 200)
        ->assertSet('pillMin', 5)
        ->assertSet('transponderMode', 'no_rentals')
        ->assertSet('competitorsPerRow', 4);
});

it('pivot settings take priority over class defaults', function () {
    RaceClassDefault::create([
        'race_class_id' => $this->raceClass->id,
        'pill_mode' => 'auto_after_confirm',
        'pill_max' => 500,
        'pill_min' => 10,
        'transponder_mode' => 'allow_rentals',
        'competitors_per_row' => 3,
    ]);

    $this->event->raceClasses()->syncWithoutDetaching([
        $this->raceClass->id => [
            'pill_mode' => 'manual_on_confirm',
            'pill_max' => 200,
            'pill_min' => 5,
            'transponder_mode' => 'no_rentals',
            'competitors_per_row' => 4,
        ],
    ]);

    Livewire::test('race-management.setup-manager', [
        'eventId' => $this->event->id,
        'raceClassId' => $this->raceClass->id,
    ])
        ->assertSet('pillMode', 'manual_on_confirm')
        ->assertSet('pillMax', 200)
        ->assertSet('pillMin', 5)
        ->assertSet('transponderMode', 'no_rentals')
        ->assertSet('competitorsPerRow', 4);
});

it('saves settings to the pivot table', function () {
    Livewire::test('race-management.setup-manager', [
        'eventId' => $this->event->id,
        'raceClassId' => $this->raceClass->id,
    ])
        ->set('pillMode', 'auto_after_confirm')
        ->set('pillMax', 300)
        ->set('pillMin', 5)
        ->set('transponderMode', 'allow_rentals')
        ->set('competitorsPerRow', 3)
        ->call('save')
        ->assertSet('successMessage', 'Settings saved successfully.');

    $pivot = $this->event->raceClasses()
        ->where('race_class_id', $this->raceClass->id)
        ->first()
        ->pivot;

    expect($pivot->pill_mode)->toBe('auto_after_confirm')
        ->and($pivot->pill_max)->toBe(300)
        ->and($pivot->pill_min)->toBe(5)
        ->and($pivot->transponder_mode)->toBe('allow_rentals')
        ->and($pivot->competitors_per_row)->toBe(3);
});

it('saves settings as default for the class', function () {
    Livewire::test('race-management.setup-manager', [
        'eventId' => $this->event->id,
        'raceClassId' => $this->raceClass->id,
    ])
        ->set('pillMode', 'manual_on_confirm')
        ->set('pillMax', 100)
        ->set('pillMin', 1)
        ->set('transponderMode', 'no_rentals')
        ->set('competitorsPerRow', 2)
        ->call('saveAsDefault')
        ->assertSet('successMessage', 'Settings saved as default for this class.');

    $defaults = RaceClassDefault::where('race_class_id', $this->raceClass->id)->first();

    expect($defaults)->not->toBeNull()
        ->and($defaults->pill_mode)->toBe('manual_on_confirm')
        ->and($defaults->pill_max)->toBe(100)
        ->and($defaults->pill_min)->toBe(1)
        ->and($defaults->transponder_mode)->toBe('no_rentals')
        ->and($defaults->competitors_per_row)->toBe(2);
});

it('save as default also saves to pivot', function () {
    Livewire::test('race-management.setup-manager', [
        'eventId' => $this->event->id,
        'raceClassId' => $this->raceClass->id,
    ])
        ->set('pillMode', 'auto_before_selection')
        ->set('transponderMode', 'allow_rentals')
        ->call('saveAsDefault');

    $pivot = $this->event->raceClasses()
        ->where('race_class_id', $this->raceClass->id)
        ->first()
        ->pivot;

    expect($pivot->pill_mode)->toBe('auto_before_selection')
        ->and($pivot->transponder_mode)->toBe('allow_rentals');
});

it('updates existing class defaults on save as default', function () {
    RaceClassDefault::create([
        'race_class_id' => $this->raceClass->id,
        'pill_mode' => 'none',
        'pill_max' => 999,
        'pill_min' => 1,
        'transponder_mode' => 'none',
        'competitors_per_row' => 2,
    ]);

    Livewire::test('race-management.setup-manager', [
        'eventId' => $this->event->id,
        'raceClassId' => $this->raceClass->id,
    ])
        ->set('pillMode', 'auto_after_confirm')
        ->set('pillMax', 500)
        ->call('saveAsDefault');

    $defaults = RaceClassDefault::where('race_class_id', $this->raceClass->id)->first();
    expect($defaults->pill_mode)->toBe('auto_after_confirm')
        ->and($defaults->pill_max)->toBe(500);

    expect(RaceClassDefault::where('race_class_id', $this->raceClass->id)->count())->toBe(1);
});

it('clears the success message when switching categories', function () {
    Livewire::test('race-management.setup-manager', [
        'eventId' => $this->event->id,
        'raceClassId' => $this->raceClass->id,
    ])
        ->call('save')
        ->assertSet('successMessage', 'Settings saved successfully.')
        ->call('setCategory', 'transponder')
        ->assertSet('successMessage', '');
});

it('loads the setup page with optional class parameter', function () {
    $this->get(route('race-management.index', [
        'event' => $this->event,
        'raceClass' => $this->raceClass,
    ]))
        ->assertOk()
        ->assertSee('Config');
});

it('redirects setup page to first class when no class provided', function () {
    $this->get(route('race-management.index', [
        'event' => $this->event,
    ]))
        ->assertRedirect(route('race-management.index', [
            'event' => $this->event,
            'raceClass' => $this->raceClass,
        ]));
});

it('adds a new class to the event', function () {
    Livewire::test('race-management.setup-manager', [
        'eventId' => $this->event->id,
        'raceClassId' => $this->raceClass->id,
    ])
        ->set('isAddingClass', true)
        ->set('newClassName', 'Stock Novice')
        ->call('addClass');

    $newClass = RaceClass::where('name', 'Stock Novice')->first();
    expect($newClass)->not->toBeNull();
    expect($this->event->raceClasses()->where('race_class_id', $newClass->id)->exists())->toBeTrue();
});

it('reuses an existing class when adding by name', function () {
    $existing = RaceClass::factory()->create(['name' => 'Modified']);

    Livewire::test('race-management.setup-manager', [
        'eventId' => $this->event->id,
        'raceClassId' => $this->raceClass->id,
    ])
        ->set('isAddingClass', true)
        ->set('newClassName', 'Modified')
        ->call('addClass');

    expect($this->event->raceClasses()->where('race_class_id', $existing->id)->exists())->toBeTrue();
    expect(RaceClass::where('name', 'Modified')->count())->toBe(1);
});

it('validates class name is required', function () {
    Livewire::test('race-management.setup-manager', [
        'eventId' => $this->event->id,
        'raceClassId' => $this->raceClass->id,
    ])
        ->set('isAddingClass', true)
        ->set('newClassName', '')
        ->call('addClass')
        ->assertHasErrors(['newClassName' => 'required']);
});
