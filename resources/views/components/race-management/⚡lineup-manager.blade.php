<?php

use App\Models\LineupScript;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    /** @var array<int, array{id: int, number: string, driver: string, city: string}> */
    public array $unassignedEntries = [];

    /** @var array<int, array{id: int, name: string, entries: array}> */
    public array $heats = [];

    public string $filter = '';
    public string $lineupMethod = '';
    public int $rowCount = 5;
    public int $lineupsPerRow = 3;
    public string $entryNumberInput = '';
    public int $activeHeat = 0;
    public int $heatCount = 4;
    public bool $showScriptPicker = false;
    public string $raceTypeLabel = 'Heat';

    public function mount(): void
    {
        $this->unassignedEntries = [
            ['id' => 1, 'number' => '24', 'driver' => 'Allison, Kobe', 'city' => 'Lima, OH'],
            ['id' => 2, 'number' => '5', 'driver' => 'Christie, D.J.', 'city' => 'Beachville, ON'],
            ['id' => 3, 'number' => '6', 'driver' => 'Coniam, Ryan', 'city' => 'Burlington, ON'],
            ['id' => 4, 'number' => '85', 'driver' => 'Daggett, Dustin', 'city' => 'Portland, MI'],
            ['id' => 5, 'number' => '23', 'driver' => 'Dobie, Devon', 'city' => 'Wapakoneta, OH'],
            ['id' => 6, 'number' => '12DD', 'driver' => 'Dryden, Darren', 'city' => 'Freelton, ON'],
            ['id' => 7, 'number' => '7', 'driver' => 'Gledhill, Eric', 'city' => 'Thamesford, ON'],
            ['id' => 8, 'number' => '7C', 'driver' => 'Gressman, Phil', 'city' => 'Fremont, OH'],
            ['id' => 9, 'number' => '01', 'driver' => 'Gunn, Tyler', 'city' => 'Napoleon, OH'],
            ['id' => 10, 'number' => '13', 'driver' => 'Gurley, Van Jr', 'city' => 'Valparaiso, IN'],
            ['id' => 11, 'number' => '77X', 'driver' => 'Hill, Alex', 'city' => 'Dublin, OH'],
            ['id' => 12, 'number' => '15', 'driver' => 'Turner, Ryan', 'city' => 'Troy, OH'],
            ['id' => 13, 'number' => '9', 'driver' => 'Martin, Liam', 'city' => 'Columbus, OH'],
            ['id' => 14, 'number' => '51X', 'driver' => 'Hickle, J.J.', 'city' => 'Findlay, OH'],
            ['id' => 15, 'number' => '45', 'driver' => 'Sheridan, Nick', 'city' => 'Toledo, OH'],
            ['id' => 16, 'number' => '98', 'driver' => 'Trenca, Joe', 'city' => 'Sandusky, OH'],
            ['id' => 17, 'number' => '77', 'driver' => 'Powless, Tyeller', 'city' => 'Brantford, ON'],
            ['id' => 18, 'number' => '49', 'driver' => 'Ruhlman, Brian', 'city' => 'Clyde, OH'],
            ['id' => 19, 'number' => '40X', 'driver' => 'Helms, Caleb', 'city' => 'Findlay, OH'],
            ['id' => 20, 'number' => '11N', 'driver' => 'Jedrzejek, Kasey', 'city' => 'Toledo, OH'],
            ['id' => 21, 'number' => '71H', 'driver' => 'Stambaugh, Max', 'city' => 'Elida, OH'],
            ['id' => 22, 'number' => 'X', 'driver' => 'Keegan, Mike', 'city' => 'London, ON'],
            ['id' => 23, 'number' => '12B', 'driver' => 'Hall, Luke', 'city' => 'Kitchener, ON'],
            ['id' => 24, 'number' => '86', 'driver' => 'Pursley, Daison', 'city' => 'Lima, OH'],
            ['id' => 25, 'number' => '19', 'driver' => 'Mann, Jett', 'city' => 'Findlay, OH'],
            ['id' => 26, 'number' => '77E', 'driver' => 'VanEvery, Mike', 'city' => 'Brantford, ON'],
            ['id' => 27, 'number' => '17', 'driver' => 'Horstman, Jared', 'city' => 'Cloverdale, OH'],
            ['id' => 28, 'number' => '51', 'driver' => 'Haggenbottom, Mike', 'city' => 'London, ON'],
            ['id' => 29, 'number' => '11H', 'driver' => 'Harmon, Caleb', 'city' => 'Lima, OH'],
            ['id' => 30, 'number' => '67', 'driver' => 'Martens, Kevin', 'city' => 'Kitchener, ON'],
            ['id' => 31, 'number' => '16B', 'driver' => 'Williams, Ty', 'city' => 'Dublin, OH'],
            ['id' => 32, 'number' => '22', 'driver' => 'Shaffer, Aaron', 'city' => 'Springfield, OH'],
        ];

        $this->initializeHeats();
    }

    /**
     * Create empty heats based on heatCount.
     */
    private function initializeHeats(): void
    {
        $this->heats = [];

        for ($h = 0; $h < $this->heatCount; $h++) {
            $this->heats[] = [
                'id' => $h + 1,
                'name' => $this->raceTypeLabel . ' ' . ($h + 1),
                'entries' => [],
            ];
        }
    }

    /**
     * Add an additional empty heat.
     */
    public function addHeat(): void
    {
        $nextId = count($this->heats) + 1;
        $this->heats[] = [
            'id' => $nextId,
            'name' => $this->raceTypeLabel . ' ' . $nextId,
            'entries' => [],
        ];
        $this->heatCount = count($this->heats);
    }

    /**
     * Remove the last heat, returning its entries to unassigned.
     */
    public function removeHeat(): void
    {
        if (count($this->heats) <= 1) {
            return;
        }

        $lastHeat = array_pop($this->heats);

        foreach ($lastHeat['entries'] as $entry) {
            if ($entry !== null) {
                $this->unassignedEntries[] = $entry;
            }
        }

        $this->heatCount = count($this->heats);

        if ($this->activeHeat >= count($this->heats)) {
            $this->activeHeat = count($this->heats) - 1;
        }
    }

    /**
     * Show the lineup script picker in the main content area.
     */
    public function showScripts(): void
    {
        $this->showScriptPicker = true;
    }

    /**
     * Cancel the script picker and return to heat cards.
     */
    public function cancelScriptPicker(): void
    {
        $this->showScriptPicker = false;
    }

    /**
     * Update the race type label and rename all existing heats.
     */
    #[On('race-type-selected')]
    public function onRaceTypeSelected(string $label): void
    {
        $this->raceTypeLabel = $label;

        foreach ($this->heats as $index => &$heat) {
            $heat['name'] = $label . ' ' . ($index + 1);
        }

        unset($heat);
        unset($this->lineupScripts);

        $this->showScriptPicker = false;
    }

    /**
     * Select a lineup script, run its calculation, and close the picker.
     */
    public function selectScript(int $scriptId): void
    {
        $script = LineupScript::query()->where('id', $scriptId)->where('is_active', true)->first();

        if (! $script) {
            return;
        }

        $this->lineupMethod = $script->name;
        $this->calculateLineups($script);
        $this->showScriptPicker = false;
    }

    /**
     * Calculate lineups using the script's strategy to order entries, then distribute into heats.
     */
    private function calculateLineups(LineupScript $script): void
    {
        $allEntries = $this->gatherAllEntries();

        if (count($allEntries) === 0) {
            return;
        }

        $strategy = $script->resolveStrategy();
        $allEntries = $strategy->order($allEntries, $script->config ?? []);

        $this->distributeIntoHeats($allEntries);
    }

    /**
     * Gather all entries from unassigned list and existing heats.
     *
     * @return array<int, array{id: int, number: string, driver: string, city: string}>
     */
    private function gatherAllEntries(): array
    {
        $allEntries = $this->unassignedEntries;

        foreach ($this->heats as $heat) {
            foreach ($heat['entries'] as $entry) {
                if ($entry !== null) {
                    $allEntries[] = $entry;
                }
            }
        }

        return $allEntries;
    }

    /**
     * Distribute entries evenly across heats.
     *
     * @param  array<int, array{id: int, number: string, driver: string, city: string}>  $entries
     */
    private function distributeIntoHeats(array $entries): void
    {
        $positionsPerHeat = $this->rowCount * 2;
        $totalEntries = count($entries);
        $heatCount = max(count($this->heats), (int) ceil($totalEntries / $positionsPerHeat));

        $this->heats = [];
        $entryIndex = 0;

        for ($h = 0; $h < $heatCount; $h++) {
            $heatEntries = [];

            for ($p = 0; $p < $positionsPerHeat; $p++) {
                if ($entryIndex < $totalEntries) {
                    $heatEntries[] = $entries[$entryIndex];
                    $entryIndex++;
                }
            }

            $this->heats[] = [
                'id' => $h + 1,
                'name' => $this->raceTypeLabel . ' ' . ($h + 1),
                'entries' => $heatEntries,
            ];
        }

        $this->unassignedEntries = [];
        $this->heatCount = count($this->heats);
    }

    /**
     * Handle sorting/reordering within the unassigned entry list.
     */
    public function sortEntryList(string|int $item, int $position): void
    {
        $item = (int) $item;

        // Find the entry in the unassigned list
        foreach ($this->unassignedEntries as $index => $entry) {
            if ($entry['id'] === $item) {
                array_splice($this->unassignedEntries, $index, 1);
                array_splice($this->unassignedEntries, $position, 0, [$entry]);

                return;
            }
        }
    }

    /**
     * Handle reordering within a heat. Finds which heat owns the entry and reorders in-place.
     */
    public function sortHeat(string|int $item, int $position): void
    {
        $item = (int) $item;

        foreach ($this->heats as $hIndex => $heat) {
            foreach ($heat['entries'] as $eIndex => $entry) {
                if ($entry['id'] === $item) {
                    array_splice($this->heats[$hIndex]['entries'], $eIndex, 1);
                    array_splice($this->heats[$hIndex]['entries'], $position, 0, [$entry]);

                    return;
                }
            }
        }
    }

    /**
     * Universal drop handler: moves an entry from anywhere to any target.
     * Target is 'entrylist' or a heat index (int). Position is the exact slot index.
     * If a heat is full, it grows to accommodate the new entry.
     */
    public function dropEntry(int $entryId, string $target, int $position = -1): void
    {
        $entry = $this->findAndRemoveEntry($entryId);

        if ($entry === null) {
            return;
        }

        if ($target === 'entrylist') {
            $this->unassignedEntries[] = $entry;
        } else {
            $heatIndex = (int) $target;
            if (isset($this->heats[$heatIndex])) {
                if ($position >= 0) {
                    $pos = min($position, count($this->heats[$heatIndex]['entries']));
                    array_splice($this->heats[$heatIndex]['entries'], $pos, 0, [$entry]);
                } else {
                    $this->heats[$heatIndex]['entries'][] = $entry;
                }
            }
        }
    }

    /**
     * Add an entry from the unassigned list to the active heat by clicking it.
     */
    public function addEntryToActiveHeat(int $entryId): void
    {
        if (! isset($this->heats[$this->activeHeat])) {
            return;
        }

        $entry = $this->findAndRemoveEntry($entryId);

        if ($entry === null) {
            return;
        }

        $this->heats[$this->activeHeat]['entries'][] = $entry;
    }

    /**
     * Set the active heat for click-to-add.
     */
    public function setActiveHeat(int $heatIndex): void
    {
        $this->activeHeat = $heatIndex;
    }

    /**
     * Move an entry from the unassigned list into a specific heat.
     */
    public function addToHeat(int $entryId, int $heatIndex): void
    {
        foreach ($this->unassignedEntries as $index => $entry) {
            if ($entry['id'] === $entryId) {
                array_splice($this->unassignedEntries, $index, 1);
                $this->heats[$heatIndex]['entries'][] = $entry;

                return;
            }
        }
    }

    /**
     * Remove an entry from a heat and return it to the unassigned list.
     */
    public function removeFromHeat(int $heatIndex, int $entryId): void
    {
        foreach ($this->heats[$heatIndex]['entries'] as $eIndex => $entry) {
            if ($entry !== null && $entry['id'] === $entryId) {
                array_splice($this->heats[$heatIndex]['entries'], $eIndex, 1);
                $this->unassignedEntries[] = $entry;

                return;
            }
        }
    }

    /**
     * Move an entry from one heat to another.
     */
    public function moveToHeat(int $entryId, int $fromHeatIndex, int $toHeatIndex): void
    {
        foreach ($this->heats[$fromHeatIndex]['entries'] as $eIndex => $entry) {
            if ($entry['id'] === $entryId) {
                array_splice($this->heats[$fromHeatIndex]['entries'], $eIndex, 1);
                $this->heats[$toHeatIndex]['entries'][] = $entry;

                return;
            }
        }
    }

    /**
     * Clear all lineups and return entries to unassigned list, keeping heats empty.
     */
    public function clearLineups(): void
    {
        foreach ($this->heats as $heat) {
            foreach ($heat['entries'] as $entry) {
                if ($entry !== null) {
                    $this->unassignedEntries[] = $entry;
                }
            }
        }

        $this->initializeHeats();
        $this->activeHeat = 0;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, LineupScript>
     */
    #[Computed]
    public function lineupScripts(): \Illuminate\Database\Eloquent\Collection
    {
        return LineupScript::query()
            ->where('is_active', true)
            ->where('race_type', $this->raceTypeLabel)
            ->orderBy('sort_order')
            ->get();
    }

    #[Computed]
    public function filteredEntries(): array
    {
        if (empty($this->filter)) {
            return $this->unassignedEntries;
        }

        $filter = strtolower($this->filter);

        return array_values(array_filter($this->unassignedEntries, function (array $entry) use ($filter): bool {
            return str_contains(strtolower($entry['driver']), $filter)
                || str_contains(strtolower($entry['number']), $filter)
                || str_contains(strtolower($entry['city']), $filter);
        }));
    }

    /**
     * Find an entry by ID and remove it from wherever it currently lives.
     *
     * @return array{id: int, number: string, driver: string, city: string}|null
     */
    private function findAndRemoveEntry(int $entryId): ?array
    {
        // Check unassigned entries
        foreach ($this->unassignedEntries as $index => $entry) {
            if ($entry['id'] === $entryId) {
                array_splice($this->unassignedEntries, $index, 1);

                return $entry;
            }
        }

        // Check heats
        foreach ($this->heats as $hIndex => $heat) {
            foreach ($heat['entries'] as $eIndex => $entry) {
                if ($entry !== null && $entry['id'] === $entryId) {
                    array_splice($this->heats[$hIndex]['entries'], $eIndex, 1);

                    return $entry;
                }
            }
        }

        return null;
    }
};
?>

<div class="flex flex-1 overflow-hidden min-h-0" x-data="{
    dragId: null,
    hoverTarget: null,
    _dragGhost: null,
    touchClone: null,
    touchEntryId: null,
    touchStartX: 0,
    touchStartY: 0,
    touchActive: false,
    touchOffsetX: 0,
    touchOffsetY: 0,

    startDrag(e, id, label) {
        this.dragId = id;
        e.dataTransfer.effectAllowed = 'move';

        // Create a small custom drag ghost
        let ghost = document.createElement('div');
        ghost.textContent = label;
        ghost.style.cssText = 'position:fixed;left:-9999px;top:-9999px;padding:6px 14px;border-radius:6px;background:#0ea5e9;color:#fff;font-size:14px;font-weight:700;white-space:nowrap;box-shadow:0 2px 8px rgba(0,0,0,.2);pointer-events:none;z-index:9999;';
        document.body.appendChild(ghost);
        e.dataTransfer.setDragImage(ghost, ghost.offsetWidth / 2, ghost.offsetHeight / 2);
        this._dragGhost = ghost;
    },

    endDrag() {
        this.dragId = null;
        this.hoverTarget = null;
        if (this._dragGhost) { this._dragGhost.remove(); this._dragGhost = null; }
    },

    onTouchStart(e, entryId, label) {
        this.touchEntryId = entryId;
        this._touchLabel = label || '#' + entryId;
        this.touchStartX = e.touches[0].clientX;
        this.touchStartY = e.touches[0].clientY;
        this.touchActive = false;
    },

    onTouchMove(e) {
        if (!this.touchEntryId) return;
        let touch = e.touches[0];
        let dx = touch.clientX - this.touchStartX;
        let dy = touch.clientY - this.touchStartY;

        if (!this.touchActive && (Math.abs(dx) > 8 || Math.abs(dy) > 8)) {
            this.touchActive = true;
            this.dragId = this.touchEntryId;
            let ghost = document.createElement('div');
            ghost.textContent = this._touchLabel;
            ghost.style.cssText = 'position:fixed;pointer-events:none;z-index:9999;padding:6px 14px;border-radius:6px;background:#0ea5e9;color:#fff;font-size:14px;font-weight:700;white-space:nowrap;box-shadow:0 2px 8px rgba(0,0,0,.2);';
            ghost.style.left = (touch.clientX - 40) + 'px';
            ghost.style.top = (touch.clientY - 20) + 'px';
            this.touchOffsetX = 40;
            this.touchOffsetY = 20;
            document.body.appendChild(ghost);
            this.touchClone = ghost;
        }

        if (this.touchActive) {
            e.preventDefault();
            if (this.touchClone) {
                this.touchClone.style.left = (touch.clientX - this.touchOffsetX) + 'px';
                this.touchClone.style.top = (touch.clientY - this.touchOffsetY) + 'px';
            }
            if (this.touchClone) this.touchClone.style.display = 'none';
            let under = document.elementFromPoint(touch.clientX, touch.clientY);
            if (this.touchClone) this.touchClone.style.display = '';
            if (under) {
                let zone = under.closest('[data-drop-zone]');
                this.hoverTarget = zone ? zone.dataset.dropZone : null;
            }
        }
    },

    onTouchEnd(e) {
        if (this.touchActive && this.dragId && this.hoverTarget) {
            if (this.hoverTarget === 'entrylist') {
                $wire.dropEntry(this.dragId, 'entrylist');
            } else {
                let m = this.hoverTarget.match(/^h(\d+)p(\d+)$/);
                if (m) {
                    $wire.dropEntry(this.dragId, m[1], parseInt(m[2]));
                }
            }
        }
        if (this.touchClone) { this.touchClone.remove(); this.touchClone = null; }
        this.dragId = null;
        this.hoverTarget = null;
        this.touchEntryId = null;
        this.touchActive = false;
    }
}">
    <!-- Left Panel - Entry List -->
    <div
        class="w-64 lg:w-72 flex flex-col bg-slate-100 dark:bg-zinc-800 border-r border-slate-200 dark:border-zinc-700 shrink-0 transition-all"
        :class="{ 'ring-2 ring-inset ring-emerald-400 bg-emerald-50 dark:bg-emerald-900/20': hoverTarget === 'entrylist' && dragId }"
        data-drop-zone="entrylist"
        x-on:dragover.prevent="hoverTarget = 'entrylist'"
        x-on:drop.prevent="if (dragId) { $wire.dropEntry(dragId, 'entrylist'); dragId = null; hoverTarget = null; }"
    >
        <!-- Toolbar -->
        <div class="p-3 border-b border-slate-200 dark:border-zinc-700 space-y-2">
            <button
                wire:click="showScripts"
                class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-lg transition-colors data-loading:opacity-50"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
                Calculate Lineups
            </button>

            @if(count($heats) > 0)
                <button
                    wire:click="clearLineups"
                    wire:confirm="Clear all lineups and return entries to the list?"
                    class="w-full flex items-center justify-center gap-2 px-3 py-2 border border-slate-300 dark:border-zinc-600 text-slate-700 dark:text-slate-300 text-sm font-medium rounded-lg hover:bg-slate-50 dark:hover:bg-zinc-700 transition-colors"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Clear All
                </button>
            @endif
        </div>

        @if($lineupMethod)
        <div class="px-3 py-2 bg-slate-50 dark:bg-zinc-700/50 border-b border-slate-200 dark:border-zinc-700">
            <p class="text-xs text-slate-500 dark:text-slate-400 text-center">
                Using: <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $lineupMethod }}</span>
            </p>
        </div>
        @endif

        <!-- Entry List Header -->
        <div class="px-3 py-3 border-b border-slate-200 dark:border-zinc-700">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Entry List</span>
                <span class="text-sm font-semibold text-slate-500 dark:text-slate-400">{{ count($unassignedEntries) }}</span>
            </div>
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="filter"
                    placeholder="Filter..."
                    class="w-full pl-9 pr-3 py-2 text-sm border border-slate-300 dark:border-zinc-600 rounded-lg bg-white dark:bg-zinc-700 text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:outline-hidden focus:ring-2 focus:ring-emerald-500"
                />
            </div>
        </div>

        <!-- Entry Items (draggable + clickable) -->
        <div class="flex-1 overflow-y-auto">
            @foreach($this->filteredEntries as $entry)
                <div
                    wire:key="entry-{{ $entry['id'] }}"
                    draggable="true"
                    x-on:dragstart="startDrag($event, {{ $entry['id'] }}, '#{{ $entry['number'] }} {{ $entry['driver'] }}')"
                    x-on:dragend="endDrag()"
                    x-on:touchstart="onTouchStart($event, {{ $entry['id'] }}, '#{{ $entry['number'] }} {{ $entry['driver'] }}')"
                    x-on:touchmove="onTouchMove($event)"
                    x-on:touchend="onTouchEnd($event)"
                    wire:click="addEntryToActiveHeat({{ $entry['id'] }})"
                    class="flex items-center gap-3 px-3 py-3 border-b border-slate-200 dark:border-zinc-700/50 hover:bg-sky-100 dark:hover:bg-sky-900/10 cursor-pointer active:bg-sky-200 dark:active:bg-sky-900/20 transition-colors"
                >
                    <div class="flex-1 min-w-0">
                        <p class="text-base font-bold text-slate-900 dark:text-white truncate leading-tight">{{ $entry['driver'] }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 truncate mt-0.5">{{ $entry['city'] }}</p>
                    </div>
                    <span class="text-2xl font-black text-slate-900 dark:text-white tabular-nums shrink-0">{{ $entry['number'] }}</span>
                </div>
            @endforeach

            @if(count($this->filteredEntries) === 0 && count($unassignedEntries) === 0)
                <div class="p-6 text-center text-sm text-slate-400 dark:text-slate-500">All entries assigned to heats</div>
            @elseif(count($this->filteredEntries) === 0)
                <div class="p-6 text-center text-sm text-slate-400 dark:text-slate-500">No entries match filter</div>
            @endif
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col bg-slate-100 dark:bg-zinc-900 min-h-0">
        @if($showScriptPicker)
        <!-- Script Picker View -->
        <div class="flex-1 overflow-y-auto p-5">
            <div class="max-w-3xl mx-auto">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-xl font-black text-slate-900 dark:text-white">Select Lineup Script</h2>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Choose a method to calculate {{ strtolower($raceTypeLabel) }} lineups</p>
                    </div>
                    <button
                        wire:click="cancelScriptPicker"
                        class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-slate-600 dark:text-slate-300 bg-white dark:bg-zinc-800 border border-slate-300 dark:border-zinc-600 rounded-lg hover:bg-slate-50 dark:hover:bg-zinc-700 transition-colors"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back
                    </button>
                </div>

                <div class="space-y-6">
                    <div>
                        <h3 class="text-sm font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3">{{ $raceTypeLabel }} Scripts</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($this->lineupScripts as $script)
                                    <button
                                        wire:click="selectScript({{ $script->id }})"
                                        class="group flex items-start gap-4 p-5 bg-white dark:bg-zinc-800 border-2 border-slate-200 dark:border-zinc-700 rounded-xl text-left hover:border-emerald-500 hover:shadow-lg dark:hover:border-emerald-500 transition-all"
                                    >
                                        <div class="shrink-0 w-12 h-12 rounded-xl flex items-center justify-center bg-slate-100 dark:bg-zinc-700 group-hover:bg-emerald-100 dark:group-hover:bg-emerald-900/30 transition-colors">
                                            @if($script->icon === 'grid')
                                                <svg class="w-6 h-6 text-slate-500 dark:text-slate-400 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                                            @elseif($script->icon === 'shuffle')
                                                <svg class="w-6 h-6 text-slate-500 dark:text-slate-400 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" /></svg>
                                            @elseif($script->icon === 'clock')
                                                <svg class="w-6 h-6 text-slate-500 dark:text-slate-400 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                            @elseif($script->icon === 'trophy')
                                                <svg class="w-6 h-6 text-slate-500 dark:text-slate-400 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" /></svg>
                                            @elseif($script->icon === 'arrows')
                                                <svg class="w-6 h-6 text-slate-500 dark:text-slate-400 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" /></svg>
                                            @elseif($script->icon === 'dice')
                                                <svg class="w-6 h-6 text-slate-500 dark:text-slate-400 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-base font-bold text-slate-900 dark:text-white group-hover:text-emerald-700 dark:group-hover:text-emerald-400 transition-colors">{{ $script->name }}</h3>
                                            <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">{{ $script->description }}</p>
                                        </div>
                                        <svg class="w-5 h-5 shrink-0 text-slate-300 dark:text-zinc-600 group-hover:text-emerald-500 transition-colors mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @else
        <!-- Heat Cards -->
        <div class="flex-1 overflow-y-auto p-5">
                <div class="grid gap-5" style="grid-template-columns: repeat({{ $lineupsPerRow }}, minmax(0, 1fr))">
                    @foreach($heats as $heatIndex => $heat)
                        <div
                            class="bg-white dark:bg-zinc-800 rounded-xl border-2 shadow-md overflow-hidden transition-colors cursor-pointer {{ $activeHeat === $heatIndex ? 'border-sky-500 ring-2 ring-sky-500/30' : 'border-slate-200 dark:border-zinc-700' }}"
                            wire:key="heat-{{ $heat['id'] }}"
                            wire:click="setActiveHeat({{ $heatIndex }})"
                        >
                            <!-- Heat Header -->
                            <div
                                class="flex items-center gap-3 px-5 py-3 transition-colors {{ $activeHeat === $heatIndex ? 'bg-sky-700 dark:bg-sky-800' : 'bg-slate-800 dark:bg-zinc-900 hover:bg-slate-700 dark:hover:bg-zinc-800' }}"
                            >
                                <svg class="w-5 h-5 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                                <h3 class="text-lg font-black text-white tracking-wide">{{ $heat['name'] }}</h3>
                            </div>

                            <!-- Column Headers -->
                            <div class="grid grid-cols-[24px_1fr_1fr]">
                                <div class="bg-slate-100 dark:bg-zinc-700/80 border-b border-r border-slate-200 dark:border-zinc-600"></div>
                                <div class="px-4 py-2 bg-slate-100 dark:bg-zinc-700/80 text-center border-b border-r border-slate-200 dark:border-zinc-600">
                                    <span class="text-sm font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider">Inside</span>
                                </div>
                                <div class="px-4 py-2 bg-slate-100 dark:bg-zinc-700/80 text-center border-b border-slate-200 dark:border-zinc-600">
                                    <span class="text-sm font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wider">Outside</span>
                                </div>
                            </div>

                            <!-- Heat Rows -->
                            @php
                                $entries = $heat['entries'];
                                $neededRows = count($entries) > 0 ? (int) ceil(count($entries) / 2) + 1 : 0;
                                $displayRows = max($rowCount, $neededRows);
                            @endphp
                            @for($row = 0; $row < $displayRows; $row++)
                                @php
                                    $insideIdx = $row * 2;
                                    $outsideIdx = $row * 2 + 1;
                                    $insideEntry = $entries[$insideIdx] ?? null;
                                    $outsideEntry = $entries[$outsideIdx] ?? null;
                                    $insideKey = 'h' . $heatIndex . 'p' . $insideIdx;
                                    $outsideKey = 'h' . $heatIndex . 'p' . $outsideIdx;
                                @endphp
                                <div class="grid grid-cols-[24px_1fr_1fr] border-b border-slate-100 dark:border-zinc-700/50 last:border-b-0">
                                    <!-- Row Number -->
                                    <div class="flex items-center justify-center bg-slate-50 dark:bg-zinc-800/80 border-r border-slate-200 dark:border-zinc-700 text-xs font-bold text-slate-400 dark:text-slate-500">
                                        {{ $row + 1 }}
                                    </div>

                                    <!-- Inside Position (drop target) -->
                                    <div
                                        class="relative border-r border-slate-200 dark:border-zinc-700 p-1 min-h-[68px] transition-all"
                                        :class="{ 'bg-sky-100 dark:bg-sky-900/30 ring-2 ring-inset ring-sky-400': hoverTarget === '{{ $insideKey }}' && dragId }"
                                        data-drop-zone="{{ $insideKey }}"
                                        x-on:dragover.prevent="hoverTarget = '{{ $insideKey }}'"
                                        x-on:drop.prevent.stop="if (dragId) { $wire.dropEntry(dragId, '{{ $heatIndex }}', {{ $insideIdx }}); dragId = null; hoverTarget = null; }"
                                    >
                                        @if($insideEntry)
                                            <div
                                                draggable="true"
                                                x-on:dragstart="startDrag($event, {{ $insideEntry['id'] }}, '#{{ $insideEntry['number'] }}')"
                                                x-on:dragend="endDrag()"
                                                x-on:touchstart="onTouchStart($event, {{ $insideEntry['id'] }}, '#{{ $insideEntry['number'] }}')"
                                                x-on:touchmove="onTouchMove($event)"
                                                x-on:touchend="onTouchEnd($event)"
                                                class="group relative bg-white dark:bg-zinc-700 border-2 border-slate-200 dark:border-zinc-600 rounded-md px-1.5 py-1.5 cursor-grab active:cursor-grabbing hover:border-sky-400 hover:shadow-md transition-all h-full flex flex-col items-center justify-center text-center"
                                            >
                                                <!-- Position Badge (left corner, black) -->
                                                <span class="absolute top-0.5 left-0.5 flex items-center justify-center w-4 h-4 bg-slate-900 dark:bg-black text-white text-[9px] font-black rounded-sm">
                                                    {{ $insideIdx + 1 }}
                                                </span>
                                                <!-- X Remove Button (right corner) -->
                                                <button
                                                    draggable="false"
                                                    wire:click.stop="dropEntry({{ $insideEntry['id'] }}, 'entrylist')"
                                                    class="absolute top-0.5 right-0.5 w-5 h-5 flex items-center justify-center rounded-sm bg-red-500 hover:bg-red-600 text-white text-xs font-bold opacity-100 [@media(hover:hover)]:opacity-0 [@media(hover:hover)]:group-hover:opacity-100 transition-opacity z-10"
                                                    title="Remove"
                                                >&times;</button>
                                                <!-- Big Number -->
                                                <span class="text-xl font-black text-slate-900 dark:text-white tabular-nums leading-none">{{ $insideEntry['number'] }}</span>
                                                <!-- Driver Name -->
                                                <span class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5 truncate w-full leading-tight">{{ $insideEntry['driver'] }}</span>
                                            </div>
                                            <!-- Drop overlay for occupied cells -->
                                            <div
                                                x-show="hoverTarget === '{{ $insideKey }}' && dragId && dragId !== {{ $insideEntry['id'] }}"
                                                x-cloak
                                                class="absolute inset-0.5 rounded-md bg-sky-100/80 dark:bg-sky-800/40 border-2 border-dashed border-sky-400 flex items-center justify-center z-20 pointer-events-none"
                                            >
                                                <span class="text-[10px] font-bold text-sky-600 dark:text-sky-300">Drop Here</span>
                                            </div>
                                        @else
                                            <div class="h-full rounded-md border-2 border-dashed flex items-center justify-center min-h-[56px] transition-colors" :class="hoverTarget === '{{ $insideKey }}' && dragId ? 'border-sky-400 bg-sky-50 dark:bg-sky-900/20' : 'border-slate-200 dark:border-zinc-700'" data-drop-zone="{{ $insideKey }}">
                                                <span class="text-[10px] font-bold" :class="hoverTarget === '{{ $insideKey }}' && dragId ? 'text-sky-500' : 'text-slate-300 dark:text-zinc-600'" x-text="hoverTarget === '{{ $insideKey }}' && dragId ? 'Drop Here' : '{{ $insideIdx + 1 }}'"></span>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Outside Position (drop target) -->
                                    <div
                                        class="relative p-1 min-h-[68px] transition-all"
                                        :class="{ 'bg-sky-100 dark:bg-sky-900/30 ring-2 ring-inset ring-sky-400': hoverTarget === '{{ $outsideKey }}' && dragId }"
                                        data-drop-zone="{{ $outsideKey }}"
                                        x-on:dragover.prevent="hoverTarget = '{{ $outsideKey }}'"
                                        x-on:drop.prevent.stop="if (dragId) { $wire.dropEntry(dragId, '{{ $heatIndex }}', {{ $outsideIdx }}); dragId = null; hoverTarget = null; }"
                                    >
                                        @if($outsideEntry)
                                            <div
                                                draggable="true"
                                                x-on:dragstart="startDrag($event, {{ $outsideEntry['id'] }}, '#{{ $outsideEntry['number'] }}')"
                                                x-on:dragend="endDrag()"
                                                x-on:touchstart="onTouchStart($event, {{ $outsideEntry['id'] }}, '#{{ $outsideEntry['number'] }}')"
                                                x-on:touchmove="onTouchMove($event)"
                                                x-on:touchend="onTouchEnd($event)"
                                                class="group relative bg-white dark:bg-zinc-700 border-2 border-slate-200 dark:border-zinc-600 rounded-md px-1.5 py-1.5 cursor-grab active:cursor-grabbing hover:border-sky-400 hover:shadow-md transition-all h-full flex flex-col items-center justify-center text-center"
                                            >
                                                <!-- Position Badge (left corner, black) -->
                                                <span class="absolute top-0.5 left-0.5 flex items-center justify-center w-4 h-4 bg-slate-900 dark:bg-black text-white text-[9px] font-black rounded-sm">
                                                    {{ $outsideIdx + 1 }}
                                                </span>
                                                <!-- X Remove Button (right corner) -->
                                                <button
                                                    draggable="false"
                                                    wire:click.stop="dropEntry({{ $outsideEntry['id'] }}, 'entrylist')"
                                                    class="absolute top-0.5 right-0.5 w-5 h-5 flex items-center justify-center rounded-sm bg-red-500 hover:bg-red-600 text-white text-xs font-bold opacity-100 [@media(hover:hover)]:opacity-0 [@media(hover:hover)]:group-hover:opacity-100 transition-opacity z-10"
                                                    title="Remove"
                                                >&times;</button>
                                                <!-- Big Number -->
                                                <span class="text-xl font-black text-slate-900 dark:text-white tabular-nums leading-none">{{ $outsideEntry['number'] }}</span>
                                                <!-- Driver Name -->
                                                <span class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5 truncate w-full leading-tight">{{ $outsideEntry['driver'] }}</span>
                                            </div>
                                            <!-- Drop overlay for occupied cells -->
                                            <div
                                                x-show="hoverTarget === '{{ $outsideKey }}' && dragId && dragId !== {{ $outsideEntry['id'] }}"
                                                x-cloak
                                                class="absolute inset-0.5 rounded-md bg-sky-100/80 dark:bg-sky-800/40 border-2 border-dashed border-sky-400 flex items-center justify-center z-20 pointer-events-none"
                                            >
                                                <span class="text-[10px] font-bold text-sky-600 dark:text-sky-300">Drop Here</span>
                                            </div>
                                        @else
                                            <div class="h-full rounded-md border-2 border-dashed flex items-center justify-center min-h-[56px] transition-colors" :class="hoverTarget === '{{ $outsideKey }}' && dragId ? 'border-sky-400 bg-sky-50 dark:bg-sky-900/20' : 'border-slate-200 dark:border-zinc-700'" data-drop-zone="{{ $outsideKey }}">
                                                <span class="text-[10px] font-bold" :class="hoverTarget === '{{ $outsideKey }}' && dragId ? 'text-sky-500' : 'text-slate-300 dark:text-zinc-600'" x-text="hoverTarget === '{{ $outsideKey }}' && dragId ? 'Drop Here' : '{{ $outsideIdx + 1 }}'"></span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endfor

                            <!-- Heat Footer -->
                            <div class="flex items-center justify-between px-4 py-2.5 bg-slate-50 dark:bg-zinc-700/30 border-t border-slate-200 dark:border-zinc-700">
                                <div class="flex items-center gap-1.5">
                                    <button class="p-1.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 hover:bg-slate-200 dark:hover:bg-zinc-600 rounded-lg transition-colors" title="Share">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" /></svg>
                                    </button>
                                    <button class="p-1.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 hover:bg-slate-200 dark:hover:bg-zinc-600 rounded-lg transition-colors" title="Copy">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                                    </button>
                                    <button class="p-1.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 hover:bg-slate-200 dark:hover:bg-zinc-600 rounded-lg transition-colors" title="Print">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
        </div>

        <!-- Bottom Toolbar (fixed at bottom) -->
        <div class="shrink-0 flex items-center gap-4 px-5 py-3 bg-white dark:bg-zinc-800 border-t border-slate-200 dark:border-zinc-700">
            <div class="flex items-center gap-2">
                <span class="text-sm font-medium text-slate-500 dark:text-slate-400">Lineups per row:</span>
                <select
                    wire:model.live="lineupsPerRow"
                    class="text-sm border border-slate-300 dark:border-zinc-600 rounded-lg px-3 py-1.5 bg-white dark:bg-zinc-700 text-slate-700 dark:text-slate-300 focus:outline-hidden focus:ring-2 focus:ring-emerald-500"
                >
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                </select>
            </div>

            <div class="flex items-center gap-1.5">
                <span class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ $raceTypeLabel }}s:</span>
                <button
                    wire:click="removeHeat"
                    class="w-7 h-7 flex items-center justify-center rounded-md border border-slate-300 dark:border-zinc-600 text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-zinc-700 transition-colors text-sm font-bold {{ count($heats) < 2 ? 'opacity-40 cursor-not-allowed' : '' }}"
                    {{ count($heats) < 2 ? 'disabled' : '' }}
                >&minus;</button>
                <span class="text-sm font-bold text-slate-700 dark:text-slate-300 tabular-nums w-6 text-center">{{ count($heats) }}</span>
                <button
                    wire:click="addHeat"
                    class="w-7 h-7 flex items-center justify-center rounded-md border border-slate-300 dark:border-zinc-600 text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-zinc-700 transition-colors text-sm font-bold"
                >+</button>
            </div>

            <div class="ml-auto flex items-center gap-4 text-sm text-slate-500 dark:text-slate-400">
                <span>{{ count($unassignedEntries) }} unassigned</span>
                <span class="text-slate-300 dark:text-zinc-600">|</span>
                @php $totalAssigned = 0; foreach ($heats as $h) { $totalAssigned += count($h['entries']); } @endphp
                <span>{{ $totalAssigned }} in {{ strtolower($raceTypeLabel) }}s</span>
            </div>
        </div>
        @endif
    </div>
</div>
