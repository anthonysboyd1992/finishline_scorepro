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
    Route::prefix('races')->name('races.')->group(function () {
        Route::view('/', 'pages.races.index')->name('index');
        Route::view('/entries', 'pages.races.entries')->name('entries');
        Route::view('/races', 'pages.races.races')->name('races');
        Route::view('/race-order', 'pages.races.race-order')->name('race-order');
        Route::view('/lineups', 'pages.races.lineups')->name('lineups');
        Route::view('/scoring', 'pages.races.scoring')->name('scoring');
    });

    // Transponder Management
    Route::prefix('transponders')->name('transponders.')->group(function () {
        Route::view('/', 'pages.transponders.index')->name('index');
    });
});

require __DIR__.'/settings.php';
