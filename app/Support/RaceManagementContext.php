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
            'index' => route('race-management.index', ['event' => $event]),
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
            'index' => route('race-management.index', ['event' => $this->event]),
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
                'label' => 'Setup',
                'href' => $setupUrl,
                'wire' => true,
                'icon' => '<svg class="w-6 h-6 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>',
            ],
            [
                'key' => 'entries',
                'label' => 'Entries',
                'href' => $classUrl('entries'),
                'wire' => true,
                'icon' => '<svg class="w-6 h-6 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>',
            ],
            [
                'key' => 'races',
                'label' => 'Races',
                'href' => $classUrl('races'),
                'wire' => true,
                'icon' => '<svg class="w-6 h-6 md:w-8 md:h-8" viewBox="0 0 24 24" fill="currentColor"><path d="M4 4v16h2V4H4zm4 0v4h2v4h2V8h2v4h2V8h2v4h2V4H8zm0 8v4h2v-4H8zm4 0v4h2v-4h-2zm4 0v4h2v-4h-2zm-8 4v4h2v-4H8zm4 0v4h2v-4h-2zm4 0v4h2v-4h-2z"/></svg>',
            ],
            [
                'key' => 'race-order',
                'label' => 'Race Order',
                'href' => $classUrl('race-order'),
                'wire' => true,
                'icon' => '<svg class="w-6 h-6 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" /></svg>',
            ],
            [
                'key' => 'lineups',
                'label' => 'Lineups',
                'href' => $raceTypeUrl('lineups'),
                'wire' => true,
                'icon' => '<svg class="w-6 h-6 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>',
            ],
            [
                'key' => 'results',
                'label' => 'Results',
                'href' => $raceTypeUrl('results'),
                'wire' => true,
                'icon' => '<svg class="w-6 h-6 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" /></svg>',
            ],
            [
                'key' => 'points-payout',
                'label' => 'Points / Payout',
                'href' => $raceTypeUrl('points-payout'),
                'wire' => true,
                'icon' => '<svg class="w-6 h-6 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
            ],
        ];
    }
}
