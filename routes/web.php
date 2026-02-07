<?php

use App\Http\Controllers\RaceManagementController;
use App\Models\Event;
use App\Models\Sanction;
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

    // Sanction switching (stores in session)
    Route::post('/sanction/switch/{sanction}', function (Sanction $sanction) {
        session(['current_sanction_id' => $sanction->id]);

        // Redirect to the first event of this sanction, or back
        $firstEvent = $sanction->events()->orderBy('event_date')->first();
        if ($firstEvent) {
            $firstClass = $firstEvent->raceClasses()->first();

            return redirect()->route('race-management.index', ['event' => $firstEvent]);
        }

        return redirect()->route('dashboard');
    })->name('sanction.switch');

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
        Route::get('/setup', 'setup')->name('index');
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
