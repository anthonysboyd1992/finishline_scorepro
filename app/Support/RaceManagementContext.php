<?php

namespace App\Support;

use App\Models\Event;
use App\Models\RaceClass;
use App\Models\Sanction;
use App\Models\Season;
use Illuminate\Support\Collection;

class RaceManagementContext
{
    public readonly Sanction $sanction;

    public readonly Season $season;

    /** @var Collection<int, Event> */
    public readonly Collection $events;

    /** @var Collection<int, RaceClass> */
    public readonly Collection $classes;

    /** @var Collection<int, Season> */
    public readonly Collection $seasons;

    private const RACE_TYPES = [
        'hotlaps' => 'Hotlaps',
        'qualifying' => 'Qualifying',
        'heat' => 'Heat',
        'dash' => 'Dash',
        'a-feature' => 'A-Feature',
        'b-feature' => 'B-Feature',
    ];

    public function __construct(
        public readonly Event $event,
        public readonly ?RaceClass $raceClass = null,
        public readonly ?string $raceType = null,
        public readonly string $currentPage = 'index',
    ) {
        $sanctionId = session('current_sanction_id');
        $this->sanction = $sanctionId
            ? Sanction::find($sanctionId) ?? Sanction::firstOrFail()
            : Sanction::firstOrFail();

        session(['current_sanction_id' => $this->sanction->id]);

        // Load all seasons for the dropdown
        $this->seasons = $this->sanction->seasons()
            ->orderByDesc('year')
            ->get();

        // Resolve the active season from session or the sanction's active season
        $seasonId = session('current_season_id');
        $resolvedSeason = $seasonId
            ? $this->seasons->firstWhere('id', $seasonId)
            : null;

        if (! $resolvedSeason) {
            $resolvedSeason = $this->seasons->firstWhere('is_active', true)
                ?? $this->seasons->first();
        }

        $this->season = $resolvedSeason ?? new Season(['year' => (int) date('Y')]);
        if ($resolvedSeason) {
            session(['current_season_id' => $resolvedSeason->id]);
        }

        // Get events from the season's schedules
        if ($resolvedSeason && $resolvedSeason->exists) {
            $scheduleIds = $resolvedSeason->schedules()->pluck('schedules.id');
            $this->events = Event::query()
                ->whereHas('schedules', fn ($q) => $q->whereIn('schedules.id', $scheduleIds))
                ->with('track')
                ->orderBy('event_date')
                ->get();
        } else {
            // Fallback to sanction events if no seasons exist
            $this->events = $this->sanction->events()
                ->with('track')
                ->orderBy('event_date')
                ->get();
        }

        $this->classes = $this->event->raceClasses()
            ->orderBy('name')
            ->get();
    }

    /**
     * Get the human-readable race type label.
     */
    public function raceTypeLabel(): string
    {
        return self::RACE_TYPES[$this->raceType] ?? 'Heat';
    }

    /**
     * Get the list of available race types as arrays for the dropdown.
     *
     * @return array<int, array{id: string, name: string, href: string}>
     */
    public function raceTypes(): array
    {
        return collect(self::RACE_TYPES)->map(fn (string $label, string $slug) => [
            'id' => $slug,
            'name' => $label,
            'href' => $this->urlForRaceType($slug),
        ])->values()->all();
    }

    /**
     * Get the current race type as an array for the dropdown.
     *
     * @return array{id: string, name: string, href: string}|null
     */
    public function currentRaceTypeArray(): ?array
    {
        if (! $this->raceType) {
            return null;
        }

        return [
            'id' => $this->raceType,
            'name' => $this->raceTypeLabel(),
            'href' => '#',
        ];
    }

    /**
     * Build the seasons array for the dropdown.
     *
     * @return array<int, array{id: int, name: string, year: int, isActive: bool}>
     */
    public function seasonsForDropdown(): array
    {
        return $this->seasons->map(fn (Season $season) => [
            'id' => $season->id,
            'name' => $season->year.' Season'.($season->is_active ? ' (Active)' : ''),
            'year' => $season->year,
            'isActive' => $season->is_active,
        ])->all();
    }

    /**
     * Build the current season array for the dropdown.
     *
     * @return array{id: int, name: string, year: int}|null
     */
    public function currentSeasonArray(): ?array
    {
        if (! $this->season->exists) {
            return null;
        }

        return [
            'id' => $this->season->id,
            'name' => $this->season->year.' Season',
            'year' => $this->season->year,
        ];
    }

    /**
     * Build the events array for the dropdown.
     *
     * @return array<int, array{id: int, name: string, date: string, href: string, wire: bool}>
     */
    public function eventsForDropdown(): array
    {
        return $this->events->map(fn (Event $event) => [
            'id' => $event->id,
            'name' => $event->displayName(),
            'date' => $event->event_date?->format('M j, Y'),
            'href' => $this->urlForEvent($event),
            'wire' => true,
        ])->all();
    }

    /**
     * Build the current event array for the dropdown.
     *
     * @return array{id: int, name: string, date: string, href: string}
     */
    public function currentEventArray(): array
    {
        return [
            'id' => $this->event->id,
            'name' => $this->event->displayName(),
            'date' => $this->event->event_date?->format('M j, Y'),
            'href' => '#',
        ];
    }

    /**
     * Build the classes array for the dropdown.
     *
     * @return array<int, array{id: int, name: string, href: string, wire: bool}>
     */
    public function classesForDropdown(): array
    {
        return $this->classes->map(fn (RaceClass $class) => [
            'id' => $class->id,
            'name' => $class->name,
            'href' => $this->urlForClass($class),
            'wire' => true,
        ])->all();
    }

    /**
     * Build the current class array for the dropdown.
     *
     * @return array{id: int, name: string, href: string}|null
     */
    public function currentClassArray(): ?array
    {
        if (! $this->raceClass) {
            return null;
        }

        return [
            'id' => $this->raceClass->id,
            'name' => $this->raceClass->name,
            'href' => '#',
        ];
    }

    /**
     * Generate a URL when switching to a different event (keeps current page, class, raceType where applicable).
     */
    public function urlForEvent(Event $event): string
    {
        $firstClass = $event->raceClasses()->orderBy('name')->first();

        return match ($this->currentPage) {
            'index' => $firstClass
                ? route('race-management.index', ['event' => $event, 'raceClass' => $firstClass])
                : route('race-management.index', ['event' => $event]),
            'entries', 'races', 'race-order' => $firstClass
                ? route("race-management.{$this->currentPage}", ['event' => $event, 'raceClass' => $firstClass])
                : route('race-management.index', ['event' => $event]),
            'lineups', 'results', 'points-payout' => $firstClass
                ? route("race-management.{$this->currentPage}", ['event' => $event, 'raceClass' => $firstClass, 'raceType' => $this->raceType ?? 'heat'])
                : route('race-management.index', ['event' => $event]),
            default => route('race-management.index', ['event' => $event]),
        };
    }

    /**
     * Generate a URL when switching to a different class (keeps current page, event, raceType).
     */
    public function urlForClass(RaceClass $class): string
    {
        return match ($this->currentPage) {
            'index' => route('race-management.index', ['event' => $this->event, 'raceClass' => $class]),
            'entries', 'races', 'race-order' => route("race-management.{$this->currentPage}", ['event' => $this->event, 'raceClass' => $class]),
            'lineups', 'results', 'points-payout' => route("race-management.{$this->currentPage}", ['event' => $this->event, 'raceClass' => $class, 'raceType' => $this->raceType ?? 'heat']),
            default => route('race-management.index', ['event' => $this->event]),
        };
    }

    /**
     * Generate a URL when switching race type (keeps current page, event, class).
     */
    public function urlForRaceType(string $raceType): string
    {
        if (! $this->raceClass) {
            return '#';
        }

        return match ($this->currentPage) {
            'lineups', 'results', 'points-payout' => route("race-management.{$this->currentPage}", ['event' => $this->event, 'raceClass' => $this->raceClass, 'raceType' => $raceType]),
            default => '#',
        };
    }

    /**
     * Build the sidebar items array with correct URLs.
     *
     * @return array<int, array{key: string, label: string, href: string, wire: bool, icon: string}>
     */
    public function sidebarItems(): array
    {
        $firstClass = $this->raceClass ?? $this->classes->first();
        $raceType = $this->raceType ?? 'heat';

        $setupUrl = route('race-management.index', ['event' => $this->event]);

        $classUrl = fn (string $page) => $firstClass
            ? route("race-management.{$page}", ['event' => $this->event, 'raceClass' => $firstClass])
            : $setupUrl;

        $raceTypeUrl = fn (string $page) => $firstClass
            ? route("race-management.{$page}", ['event' => $this->event, 'raceClass' => $firstClass, 'raceType' => $raceType])
            : $setupUrl;

        return [
            [
                'key' => 'index',
                'label' => 'Config',
                'href' => $setupUrl,
                'wire' => true,
                'icon' => '<i class="fa-solid fa-gear text-xl md:text-2xl"></i>',
            ],
            [
                'key' => 'entries',
                'label' => 'Check Ins',
                'href' => $classUrl('entries'),
                'wire' => true,
                'icon' => '<i class="fa-solid fa-clipboard-check text-xl md:text-2xl"></i>',
            ],
            [
                'key' => 'races',
                'label' => 'Races',
                'href' => $classUrl('races'),
                'wire' => true,
                'icon' => '<i class="fa-solid fa-flag-checkered text-xl md:text-2xl"></i>',
            ],
            [
                'key' => 'lineups',
                'label' => 'Line Ups',
                'href' => $raceTypeUrl('lineups'),
                'wire' => true,
                'icon' => '<i class="fa-solid fa-table-cells text-xl md:text-2xl"></i>',
            ],
            [
                'key' => 'race-order',
                'label' => 'Race Order',
                'href' => $classUrl('race-order'),
                'wire' => true,
                'icon' => '<i class="fa-solid fa-list-ol text-xl md:text-2xl"></i>',
            ],
            [
                'key' => 'results',
                'label' => 'Scoring',
                'href' => $raceTypeUrl('results'),
                'wire' => true,
                'icon' => '<i class="fa-solid fa-stopwatch text-xl md:text-2xl"></i>',
            ],
            [
                'key' => 'points-payout',
                'label' => 'Reports',
                'href' => $raceTypeUrl('points-payout'),
                'wire' => true,
                'icon' => '<i class="fa-solid fa-chart-bar text-xl md:text-2xl"></i>',
            ],
        ];
    }
}
