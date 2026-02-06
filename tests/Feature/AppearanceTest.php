<?php

use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('renders the appearance settings page', function () {
    $this->actingAs($this->user)
        ->get(route('appearance.edit'))
        ->assertStatus(200)
        ->assertSee('Appearance');
});

it('loads the user appearance preference on mount', function () {
    $this->user->update(['appearance' => 'light']);

    Livewire::actingAs($this->user)
        ->test('pages::settings.appearance')
        ->assertSet('appearance', 'light');
});

it('defaults to system when no preference is set', function () {
    Livewire::actingAs($this->user)
        ->test('pages::settings.appearance')
        ->assertSet('appearance', 'system');
});

it('updates appearance preference to dark', function () {
    Livewire::actingAs($this->user)
        ->test('pages::settings.appearance')
        ->call('updateAppearance', 'dark')
        ->assertSet('appearance', 'dark');

    expect($this->user->fresh()->appearance)->toBe('dark');
});

it('updates appearance preference to light', function () {
    Livewire::actingAs($this->user)
        ->test('pages::settings.appearance')
        ->call('updateAppearance', 'light')
        ->assertSet('appearance', 'light');

    expect($this->user->fresh()->appearance)->toBe('light');
});

it('updates appearance preference to system', function () {
    $this->user->update(['appearance' => 'dark']);

    Livewire::actingAs($this->user)
        ->test('pages::settings.appearance')
        ->call('updateAppearance', 'system')
        ->assertSet('appearance', 'system');

    expect($this->user->fresh()->appearance)->toBe('system');
});

it('saves appearance via the API route', function () {
    $this->actingAs($this->user)
        ->postJson(route('appearance.update'), ['appearance' => 'dark'])
        ->assertNoContent();

    expect($this->user->fresh()->appearance)->toBe('dark');
});

it('validates the appearance API route', function () {
    $this->actingAs($this->user)
        ->postJson(route('appearance.update'), ['appearance' => 'invalid'])
        ->assertStatus(422);
});

it('requires authentication for the API route', function () {
    $this->postJson(route('appearance.update'), ['appearance' => 'dark'])
        ->assertStatus(401);
});
