<?php

use App\Http\Controllers\RaceManagementController;
use App\Models\Event;
use App\Models\Sanction;
use App\Models\Season;
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
        Route::view('/schedules', 'pages.seasons.schedules')->name('schedules');
        Route::view('/events', 'pages.seasons.events')->name('events');
        Route::view('/tracks', 'pages.seasons.tracks')->name('tracks');
    });

    // Sanction switching (stores in session, redirects to dashboard)
    Route::post('/sanction/switch/{sanction}', function (Sanction $sanction) {
        session(['current_sanction_id' => $sanction->id]);
        session()->forget('current_season_id');

        return redirect()->route('dashboard');
    })->name('sanction.switch');

    // Season switching (stores in session, redirects to first event of new season)
    Route::post('/season/switch/{season}', function (Season $season) {
        session(['current_season_id' => $season->id]);

        // Find the first event in this season's schedules
        $scheduleIds = $season->schedules()->pluck('schedules.id');
        $firstEvent = Event::query()
            ->whereHas('schedules', fn ($q) => $q->whereIn('schedules.id', $scheduleIds))
            ->orderBy('event_date')
            ->first();

        if ($firstEvent) {
            return redirect()->route('race-management.index', ['event' => $firstEvent]);
        }

        return redirect()->back();
    })->name('season.switch');

    // Race Management entry point (redirect to first event)
    Route::get('/race-management', function () {
        $sanctionId = session('current_sanction_id');
        $sanction = $sanctionId ? Sanction::find($sanctionId) : Sanction::first();

        if ($sanction) {
            session(['current_sanction_id' => $sanction->id]);
            $event = $sanction->events()->orderBy('event_date')->first();

            if ($event) {
                return redirect()->route('race-management.index', ['event' => $event]);
            }
        }

        return redirect()->route('dashboard');
    })->name('race-management.entry');

    // Race Management
    Route::prefix('race-management/{event}')->name('race-management.')->controller(RaceManagementController::class)->group(function () {
        Route::get('/setup/{raceClass?}', 'setup')->name('index');
        Route::get('/entries/{raceClass}', 'entries')->name('entries');
        Route::get('/races/{raceClass}', 'races')->name('races');
        Route::get('/race-order/{raceClass}', 'raceOrder')->name('race-order');
        Route::get('/lineups/{raceClass}/{raceType}', 'lineups')->name('lineups');
        Route::get('/results/{raceClass}/{raceType}', 'results')->name('results');
        Route::get('/points-payout/{raceClass}/{raceType}', 'pointsPayout')->name('points-payout');
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
