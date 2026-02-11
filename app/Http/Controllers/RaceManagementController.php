<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\RaceClass;
use Illuminate\View\View;

class RaceManagementController extends Controller
{
    public function setup(Event $event, ?RaceClass $raceClass = null): View
    {
        return view('pages.race-management.index', compact('event', 'raceClass'));
    }

    public function entries(Event $event, RaceClass $raceClass): View
    {
        return view('pages.race-management.entries', compact('event', 'raceClass'));
    }

    public function races(Event $event, RaceClass $raceClass): View
    {
        return view('pages.race-management.races', compact('event', 'raceClass'));
    }

    public function raceOrder(Event $event, RaceClass $raceClass): View
    {
        return view('pages.race-management.race-order', compact('event', 'raceClass'));
    }

    public function lineups(Event $event, RaceClass $raceClass, string $raceType): View
    {
        return view('pages.race-management.lineups', compact('event', 'raceClass', 'raceType'));
    }

    public function results(Event $event, RaceClass $raceClass, string $raceType): View
    {
        return view('pages.race-management.results', compact('event', 'raceClass', 'raceType'));
    }

    public function pointsPayout(Event $event, RaceClass $raceClass, string $raceType): View
    {
        return view('pages.race-management.points-payout', compact('event', 'raceClass', 'raceType'));
    }
}
