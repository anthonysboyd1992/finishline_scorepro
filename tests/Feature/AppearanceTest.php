<?php

use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('renders the appearance settings page', function () {
    $this->actingAs($this->user)
        ->get(route('appearance.edit'))
        ->assertStatus(200)
        ->assertSee('Appearance')
        ->assertSee('Dark Mode');
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
