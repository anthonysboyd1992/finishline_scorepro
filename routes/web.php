<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Module Routes (protected)
Route::middleware(['auth', 'verified'])->group(function () {
    // Season Management
    Route::prefix('seasons')->name('seasons.')->group(function () {
        Route::view('/', 'pages.seasons.index')->name('index');
    });

    // Race Management
    Route::prefix('race-management')->name('race-management.')->group(function () {
        Route::view('/', 'pages.race-management.index')->name('index');
        Route::view('/entries', 'pages.race-management.entries')->name('entries');
        Route::view('/races', 'pages.race-management.races')->name('races');
        Route::view('/race-order', 'pages.race-management.race-order')->name('race-order');
        Route::view('/lineups', 'pages.race-management.lineups')->name('lineups');
        Route::view('/results', 'pages.race-management.results')->name('results');
        Route::view('/points-payout', 'pages.race-management.points-payout')->name('points-payout');
    });

    // Transponder Management
    Route::prefix('transponders')->name('transponders.')->group(function () {
        Route::view('/', 'pages.transponders.index')->name('index');
    });
});

// Appearance toggle (used by nav bar dark mode toggle)
Route::post('/api/appearance', function (Illuminate\Http\Request $request) {
    $request->validate(['appearance' => 'required|in:light,dark,system']);
    $request->user()->update(['appearance' => $request->appearance]);

    return response()->noContent();
})->middleware('auth')->name('appearance.update');

require __DIR__.'/settings.php';
