<?php

use App\Models\Competitor;
use App\Models\Entry;
use App\Models\RaceClass;
use App\Models\Transponder;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    public int $eventId = 0;
    public int $raceClassId = 0;

    public string $search = '';
    public ?int $selectedEntryId = null;
    public ?int $selectedCompetitorId = null;

    // Sort
    public string $sortBy = 'name'; // name, car_number, pill

    // Modes
    public bool $isCreatingCompetitor = false;
    public bool $isEditingEntry = false;
    public bool $isConfirmingEntry = false;

    // Entry form fields
    public string $entryCarNumber = '';
    public ?int $entryPillNumber = null;
    public string $entryTransponder = '';
    public string $transponderResolved = '';
    public string $entryOwner = '';
    public int $entryRaceClassId = 0;

    // Competitor form fields
    public string $competitorFirstName = '';
    public string $competitorLastName = '';
    public string $competitorCarNumber = '';
    public string $competitorCity = '';
    public string $competitorState = '';
    public string $competitorCountry = 'US';
    public string $competitorPhone = '';
    public string $competitorEmail = '';

    public function mount(): void
    {
        $this->entryRaceClassId = $this->raceClassId;
    }

    /**
     * Entries for the current event + class (right panel).
     */
    #[Computed]
    public function entries(): \Illuminate\Database\Eloquent\Collection
    {
        $query = Entry::query()
            ->where('event_id', $this->eventId)
            ->where('race_class_id', $this->raceClassId)
            ->with('competitor');

        return match ($this->sortBy) {
            'car_number' => $query->orderByRaw('CAST(car_number AS INTEGER) ASC, car_number ASC')->get(),
            'pill' => $query->orderByRaw('pill_number IS NOT NULL, pill_number ASC')->get(),
            default => $query->orderBy('driver_name')->get(),
        };
    }

    /**
     * Left panel: competitor search results or all competitors by default.
     */
    #[Computed]
    public function competitors(): \Illuminate\Database\Eloquent\Collection
    {
        $enteredIds = $this->enteredCompetitorIds;

        $query = Competitor::query();

        if (strlen($this->search) >= 2) {
            $query->where(function ($q) {
                $q->where('first_name', 'like', "%{$this->search}%")
                    ->orWhere('last_name', 'like', "%{$this->search}%")
                    ->orWhere('car_number', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%");
            });
        }

        // Sort entered competitors to the end
        if (count($enteredIds) > 0) {
            $placeholders = implode(',', array_fill(0, count($enteredIds), '?'));
            $query->orderByRaw("CASE WHEN id IN ({$placeholders}) THEN 1 ELSE 0 END", $enteredIds);
        }

        $query->orderBy('last_name');

        return $query->limit(50)->get();
    }

    #[Computed]
    public function selectedEntry(): ?Entry
    {
        return $this->selectedEntryId
            ? Entry::with(['competitor', 'raceClass'])->find($this->selectedEntryId)
            : null;
    }

    #[Computed]
    public function selectedCompetitor(): ?Competitor
    {
        return $this->selectedCompetitorId
            ? Competitor::find($this->selectedCompetitorId)
            : null;
    }

    #[Computed]
    public function availableClasses(): \Illuminate\Database\Eloquent\Collection
    {
        return RaceClass::query()
            ->whereHas('events', fn ($q) => $q->where('events.id', $this->eventId))
            ->orderBy('name')
            ->get();
    }

    /**
     * IDs of competitors that already have an entry in this event+class.
     *
     * @return array<int, int>
     */
    #[Computed]
    public function enteredCompetitorIds(): array
    {
        return Entry::query()
            ->where('event_id', $this->eventId)
            ->where('race_class_id', $this->raceClassId)
            ->whereNotNull('competitor_id')
            ->pluck('competitor_id')
            ->all();
    }

    #[Computed]
    public function entryCount(): int
    {
        return Entry::query()
            ->where('event_id', $this->eventId)
            ->where('race_class_id', $this->raceClassId)
            ->count();
    }

    public function selectEntry(int $id): void
    {
        $this->selectedEntryId = $id;
        $this->isEditingEntry = false;
    }

    public function selectCompetitor(int $id): void
    {
        $this->selectedCompetitorId = $id;
        $this->isCreatingCompetitor = false;
        $this->isConfirmingEntry = true;

        $competitor = Competitor::find($id);
        if ($competitor) {
            $this->entryCarNumber = $competitor->car_number ?? '';
            $this->entryRaceClassId = $this->raceClassId;
            $this->entryPillNumber = null;
        }
        unset($this->selectedCompetitor);
    }

    public function startCreatingCompetitor(): void
    {
        $this->isCreatingCompetitor = true;
        $this->isConfirmingEntry = false;
        $this->selectedCompetitorId = null;
        $this->resetCompetitorForm();
    }

    public function saveCompetitor(): void
    {
        $this->validate([
            'competitorFirstName' => ['required', 'string', 'max:255'],
            'competitorLastName' => ['required', 'string', 'max:255'],
        ]);

        $competitor = Competitor::query()->create([
            'first_name' => $this->competitorFirstName,
            'last_name' => $this->competitorLastName,
            'car_number' => $this->competitorCarNumber ?: null,
            'city' => $this->competitorCity ?: null,
            'state' => $this->competitorState ?: null,
            'country' => $this->competitorCountry ?: 'US',
            'phone' => $this->competitorPhone ?: null,
            'email' => $this->competitorEmail ?: null,
        ]);

        $this->selectCompetitor($competitor->id);
        unset($this->competitors);
    }

    public function confirmEntry(): void
    {
        $this->validate([
            'entryCarNumber' => ['required', 'string', 'max:20'],
            'entryRaceClassId' => ['required', 'exists:race_classes,id'],
        ]);

        if (! $this->selectedCompetitor) {
            return;
        }

        $entry = Entry::query()->create([
            'competitor_id' => $this->selectedCompetitor->id,
            'event_id' => $this->eventId,
            'race_class_id' => $this->entryRaceClassId,
            'car_number' => $this->entryCarNumber,
            'pill_number' => $this->entryPillNumber,
            'transponder' => $this->entryTransponder ?: null,
            'owner' => $this->entryOwner ?: null,
            'driver_name' => $this->selectedCompetitor->displayName(),
            'city' => collect([$this->selectedCompetitor->city, $this->selectedCompetitor->state])->filter()->implode(', ') ?: null,
        ]);

        $this->selectedEntryId = $entry->id;
        $this->selectedCompetitorId = null;
        $this->isConfirmingEntry = false;
        $this->isCreatingCompetitor = false;
        $this->resetEntryForm();
        unset($this->entries, $this->entryCount, $this->selectedEntry, $this->competitors, $this->enteredCompetitorIds);
    }

    public function startEditingEntry(): void
    {
        if (! $this->selectedEntry) {
            return;
        }

        $this->isEditingEntry = true;
        $this->entryCarNumber = $this->selectedEntry->car_number;
        $this->entryPillNumber = $this->selectedEntry->pill_number;
        $this->entryTransponder = $this->selectedEntry->transponder ?? '';
        $this->transponderResolved = '';
        $this->entryOwner = $this->selectedEntry->owner ?? '';
        $this->entryRaceClassId = $this->selectedEntry->race_class_id;
    }

    public function updateEntry(): void
    {
        $this->validate([
            'entryCarNumber' => ['required', 'string', 'max:20'],
            'entryRaceClassId' => ['required', 'exists:race_classes,id'],
        ]);

        if (! $this->selectedEntry) {
            return;
        }

        $this->selectedEntry->update([
            'car_number' => $this->entryCarNumber,
            'pill_number' => $this->entryPillNumber,
            'transponder' => $this->entryTransponder ?: null,
            'owner' => $this->entryOwner ?: null,
            'race_class_id' => $this->entryRaceClassId,
        ]);

        $this->isEditingEntry = false;
        unset($this->entries, $this->selectedEntry);
    }

    public function deleteEntry(): void
    {
        if ($this->selectedEntry) {
            $this->selectedEntry->delete();
            $this->selectedEntryId = null;
            $this->isEditingEntry = false;
            unset($this->entries, $this->entryCount, $this->enteredCompetitorIds);
        }
    }

    public function deleteEntryById(int $id): void
    {
        $entry = Entry::find($id);
        if ($entry) {
            $entry->delete();
            $this->selectedEntryId = null;
            $this->isEditingEntry = false;
            unset($this->entries, $this->entryCount, $this->enteredCompetitorIds);
        }
    }

    public function editEntryById(int $id): void
    {
        $this->selectedEntryId = $id;
        unset($this->selectedEntry);
        $this->startEditingEntry();
    }

    /**
     * Look up a transponder by alias or number and resolve to the full number.
     */
    public function resolveTransponder(): void
    {
        $input = trim($this->entryTransponder);
        $this->transponderResolved = '';

        if ($input === '') {
            return;
        }

        $transponder = Transponder::query()
            ->where('alias', $input)
            ->orWhere('number', $input)
            ->first();

        if ($transponder) {
            $this->entryTransponder = $transponder->number;
            $this->transponderResolved = $transponder->alias
                ? "{$transponder->alias} → {$transponder->number}"
                : $transponder->number;
        }
    }

    public function setSortBy(string $sort): void
    {
        $this->sortBy = $sort;
        unset($this->entries);
    }

    public function drawPill(int $entryId): void
    {
        $entry = Entry::find($entryId);
        if (! $entry) {
            return;
        }

        // Get existing pill numbers for this event+class to avoid duplicates
        $usedPills = Entry::query()
            ->where('event_id', $this->eventId)
            ->where('race_class_id', $this->raceClassId)
            ->whereNotNull('pill_number')
            ->pluck('pill_number')
            ->all();

        // Draw a random pill from 1-999 that isn't already taken
        $available = array_diff(range(1, 999), $usedPills);
        if (count($available) > 0) {
            $entry->update(['pill_number' => $available[array_rand($available)]]);
        }

        unset($this->entries);
    }

    public function clearSearch(): void
    {
        $this->search = '';
        unset($this->competitors);
    }

    public function cancelConfirm(): void
    {
        $this->isConfirmingEntry = false;
        $this->isCreatingCompetitor = false;
        $this->selectedCompetitorId = null;
        $this->resetEntryForm();
    }

    public function cancelEdit(): void
    {
        $this->isEditingEntry = false;
    }

    private function resetEntryForm(): void
    {
        $this->entryCarNumber = '';
        $this->entryPillNumber = null;
        $this->entryTransponder = '';
        $this->transponderResolved = '';
        $this->entryOwner = '';
        $this->entryRaceClassId = $this->raceClassId;
    }

    private function resetCompetitorForm(): void
    {
        $this->competitorFirstName = '';
        $this->competitorLastName = '';
        $this->competitorCarNumber = '';
        $this->competitorCity = '';
        $this->competitorState = '';
        $this->competitorCountry = 'US';
        $this->competitorPhone = '';
        $this->competitorEmail = '';
    }
}; ?>

<div class="flex flex-1 overflow-hidden">
    {{-- ===== LEFT PANEL: Competitor Search / Add ===== --}}
    <div class="w-full md:w-96 lg:w-[420px] flex flex-col bg-white dark:bg-zinc-800 border-r border-slate-200 dark:border-zinc-700 shrink-0">
        <!-- Header -->
        <div class="p-4 border-b border-slate-200 dark:border-zinc-700">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Find Competitor</h2>
                <div class="flex items-center gap-2">
                    @if(strlen($search) >= 2)
                        <button
                            wire:click="clearSearch"
                            class="inline-flex items-center gap-1 px-3 py-1.5 bg-slate-200 hover:bg-slate-300 dark:bg-zinc-700 dark:hover:bg-zinc-600 text-slate-700 dark:text-slate-300 text-sm font-medium rounded-md transition-colors"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Clear
                        </button>
                    @endif
                    <button
                        wire:click="startCreatingCompetitor"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition-colors"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        New
                    </button>
                </div>
            </div>
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search by name, car #, or email..."
                    class="w-full pl-10 pr-4 py-2 border border-slate-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 text-sm focus:outline-hidden focus:ring-2 focus:ring-red-500 focus:border-transparent"
                />
            </div>
        </div>

        {{-- Competitor list --}}
        <div class="flex-1 overflow-y-auto">
            @forelse($this->competitors as $competitor)
                @php $isEntered = in_array($competitor->id, $this->enteredCompetitorIds); @endphp
                <button
                    wire:click="selectCompetitor({{ $competitor->id }})"
                    class="flex w-full items-center gap-3 px-4 py-3 border-b border-slate-100 dark:border-zinc-700/50 transition-colors text-left {{ $selectedCompetitorId === $competitor->id ? 'bg-blue-50 dark:bg-zinc-700 border-l-4 border-l-blue-500' : '' }} {{ $isEntered ? 'opacity-50' : 'hover:bg-slate-50 dark:hover:bg-zinc-700/50' }}"
                >
                    <div class="flex items-center justify-center w-10 h-10 {{ $isEntered ? 'bg-slate-400 dark:bg-zinc-600' : 'bg-blue-600' }} text-white font-bold text-sm rounded-lg shrink-0">
                        {{ $competitor->car_number ?? '?' }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-medium {{ $isEntered ? 'text-slate-400 dark:text-slate-500' : 'text-slate-900 dark:text-white' }} truncate">{{ $competitor->displayName() }}</p>
                        <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                            @if($isEntered)
                                <span class="px-1.5 py-0.5 bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 rounded text-[10px] font-medium">Entered</span>
                            @endif
                            @if($competitor->city)
                                <span>{{ $competitor->city }}{{ $competitor->state ? ', ' . $competitor->state : '' }}</span>
                            @endif
                        </div>
                    </div>
                    @if(!$isEntered)
                    <svg class="w-5 h-5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    @else
                    <svg class="w-5 h-5 text-green-500 dark:text-green-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    @endif
                </button>
            @empty
                <div class="py-8 text-center text-slate-500 dark:text-slate-400">
                    @if(strlen($search) >= 2)
                        <p class="text-sm">No competitors found for "{{ $search }}"</p>
                    @else
                        <p class="text-sm">No competitors yet</p>
                    @endif
                    <button wire:click="startCreatingCompetitor" class="mt-2 text-sm text-red-600 dark:text-red-400 hover:underline">Create New Competitor</button>
                </div>
            @endforelse
        </div>
    </div>

    {{-- ===== RIGHT PANEL: Forms or Entry List ===== --}}
    <div class="flex-1 flex flex-col bg-slate-100 dark:bg-zinc-900 overflow-hidden">
        @if($isCreatingCompetitor)
            {{-- Create New Competitor form --}}
            <div class="p-4 border-b border-slate-200 dark:border-zinc-700 bg-white dark:bg-zinc-800">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Create New Competitor</h2>
                    <button wire:click="cancelConfirm" class="text-sm text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200">Cancel</button>
                </div>
                <form wire:submit="saveCompetitor" class="space-y-3">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-0.5">First Name</label>
                            <input type="text" wire:model="competitorFirstName" placeholder="First Name" class="w-full rounded-md border border-slate-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 px-3 py-1.5 text-sm text-slate-900 dark:text-white focus:border-blue-500 focus:outline-hidden focus:ring-2 focus:ring-blue-500" />
                            @error('competitorFirstName') <p class="text-xs text-red-600 mt-0.5">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-0.5">Last Name</label>
                            <input type="text" wire:model="competitorLastName" placeholder="Last Name" class="w-full rounded-md border border-slate-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 px-3 py-1.5 text-sm text-slate-900 dark:text-white focus:border-blue-500 focus:outline-hidden focus:ring-2 focus:ring-blue-500" />
                            @error('competitorLastName') <p class="text-xs text-red-600 mt-0.5">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-0.5">Car Number (optional)</label>
                        <input type="text" wire:model="competitorCarNumber" placeholder="Car Number" class="w-full rounded-md border border-slate-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 px-3 py-1.5 text-sm text-slate-900 dark:text-white focus:border-blue-500 focus:outline-hidden focus:ring-2 focus:ring-blue-500" />
                    </div>
                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-0.5">City</label>
                            <input type="text" wire:model="competitorCity" placeholder="City" class="w-full rounded-md border border-slate-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 px-3 py-1.5 text-sm text-slate-900 dark:text-white focus:border-blue-500 focus:outline-hidden focus:ring-2 focus:ring-blue-500" />
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-0.5">State</label>
                            <input type="text" wire:model="competitorState" placeholder="State" class="w-full rounded-md border border-slate-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 px-3 py-1.5 text-sm text-slate-900 dark:text-white focus:border-blue-500 focus:outline-hidden focus:ring-2 focus:ring-blue-500" />
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-0.5">Country</label>
                            <input type="text" wire:model="competitorCountry" placeholder="Country" class="w-full rounded-md border border-slate-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 px-3 py-1.5 text-sm text-slate-900 dark:text-white focus:border-blue-500 focus:outline-hidden focus:ring-2 focus:ring-blue-500" />
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-0.5">Phone (optional)</label>
                            <input type="text" wire:model="competitorPhone" placeholder="Phone" class="w-full rounded-md border border-slate-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 px-3 py-1.5 text-sm text-slate-900 dark:text-white focus:border-blue-500 focus:outline-hidden focus:ring-2 focus:ring-blue-500" />
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-0.5">Email (optional)</label>
                            <input type="email" wire:model="competitorEmail" placeholder="Email" class="w-full rounded-md border border-slate-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 px-3 py-1.5 text-sm text-slate-900 dark:text-white focus:border-blue-500 focus:outline-hidden focus:ring-2 focus:ring-blue-500" />
                        </div>
                    </div>
                    <div class="flex gap-2 pt-1">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors text-sm font-medium">Create & Add Entry</button>
                        <button type="button" wire:click="cancelConfirm" class="px-4 py-2 bg-slate-200 dark:bg-zinc-700 text-slate-700 dark:text-slate-300 rounded-md hover:bg-slate-300 dark:hover:bg-zinc-600 transition-colors text-sm font-medium">Cancel</button>
                    </div>
                </form>
            </div>
        @elseif($isConfirmingEntry && $this->selectedCompetitor)
            {{-- Confirm Entry form --}}
            <div class="p-4 border-b border-slate-200 dark:border-zinc-700 bg-white dark:bg-zinc-800">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Confirm Entry</h2>
                    <button wire:click="cancelConfirm" class="text-sm text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200">Cancel</button>
                </div>
                <div class="flex items-center gap-3 mb-4 p-3 bg-blue-50 dark:bg-zinc-700 rounded-lg">
                    <div class="flex items-center justify-center w-12 h-12 bg-blue-600 text-white font-bold text-sm rounded-lg shrink-0">
                        {{ $this->selectedCompetitor->car_number ?? '?' }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-slate-900 dark:text-white">{{ $this->selectedCompetitor->fullName() }}</p>
                        <p class="text-sm text-slate-500 dark:text-slate-400">
                            {{ collect([$this->selectedCompetitor->city, $this->selectedCompetitor->state])->filter()->implode(', ') ?: 'No location' }}
                        </p>
                    </div>
                </div>
                <form wire:submit="confirmEntry" class="space-y-3">
                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-0.5">Car #</label>
                            <input type="text" wire:model="entryCarNumber" class="w-full rounded-md border border-slate-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 px-3 py-1.5 text-sm font-bold text-slate-900 dark:text-white focus:border-blue-500 focus:outline-hidden focus:ring-2 focus:ring-blue-500" />
                            @error('entryCarNumber') <p class="text-xs text-red-600 mt-0.5">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-0.5">Pill #</label>
                            <input type="number" wire:model="entryPillNumber" placeholder="Optional" class="w-full rounded-md border border-slate-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 px-3 py-1.5 text-sm text-slate-900 dark:text-white focus:border-blue-500 focus:outline-hidden focus:ring-2 focus:ring-blue-500" />
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-0.5">Transponder</label>
                            <input type="text" wire:model="entryTransponder" wire:blur="resolveTransponder" placeholder="# or alias" class="w-full rounded-md border border-slate-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 px-3 py-1.5 text-sm text-slate-900 dark:text-white focus:border-blue-500 focus:outline-hidden focus:ring-2 focus:ring-blue-500" />
                            @if($transponderResolved)
                                <p class="text-[10px] text-green-500 mt-0.5">{{ $transponderResolved }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-0.5">Owner</label>
                            <input type="text" wire:model="entryOwner" placeholder="Optional" class="w-full rounded-md border border-slate-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 px-3 py-1.5 text-sm text-slate-900 dark:text-white focus:border-blue-500 focus:outline-hidden focus:ring-2 focus:ring-blue-500" />
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-0.5">Class</label>
                            <select wire:model="entryRaceClassId" class="w-full rounded-md border border-slate-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 px-3 py-1.5 text-sm text-slate-900 dark:text-white focus:border-blue-500 focus:outline-hidden focus:ring-2 focus:ring-blue-500">
                                @foreach($this->availableClasses as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="flex gap-2 pt-1">
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors text-sm font-medium">Confirm Entry</button>
                        <button type="button" wire:click="cancelConfirm" class="px-4 py-2 bg-slate-200 dark:bg-zinc-700 text-slate-700 dark:text-slate-300 rounded-md hover:bg-slate-300 dark:hover:bg-zinc-600 transition-colors text-sm font-medium">Cancel</button>
                    </div>
                </form>
            </div>
        @elseif($isEditingEntry && $this->selectedEntry)
            {{-- Edit Entry form --}}
            <div class="p-4 border-b border-slate-200 dark:border-zinc-700 bg-white dark:bg-zinc-800">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Edit Entry: {{ $this->selectedEntry->driver_name }}</h2>
                    <button wire:click="cancelEdit" class="text-sm text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200">Cancel</button>
                </div>
                <form wire:submit="updateEntry" class="space-y-3">
                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-0.5">Car #</label>
                            <input type="text" wire:model="entryCarNumber" class="w-full rounded-md border border-slate-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 px-3 py-1.5 text-sm font-bold text-slate-900 dark:text-white focus:border-blue-500 focus:outline-hidden focus:ring-2 focus:ring-blue-500" />
                            @error('entryCarNumber') <p class="text-xs text-red-600 mt-0.5">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-0.5">Pill #</label>
                            <input type="number" wire:model="entryPillNumber" placeholder="Optional" class="w-full rounded-md border border-slate-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 px-3 py-1.5 text-sm text-slate-900 dark:text-white focus:border-blue-500 focus:outline-hidden focus:ring-2 focus:ring-blue-500" />
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-0.5">Transponder</label>
                            <input type="text" wire:model="entryTransponder" wire:blur="resolveTransponder" placeholder="# or alias" class="w-full rounded-md border border-slate-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 px-3 py-1.5 text-sm text-slate-900 dark:text-white focus:border-blue-500 focus:outline-hidden focus:ring-2 focus:ring-blue-500" />
                            @if($transponderResolved)
                                <p class="text-[10px] text-green-500 mt-0.5">{{ $transponderResolved }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-0.5">Owner</label>
                            <input type="text" wire:model="entryOwner" placeholder="Optional" class="w-full rounded-md border border-slate-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 px-3 py-1.5 text-sm text-slate-900 dark:text-white focus:border-blue-500 focus:outline-hidden focus:ring-2 focus:ring-blue-500" />
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-600 dark:text-slate-400 mb-0.5">Class</label>
                            <select wire:model="entryRaceClassId" class="w-full rounded-md border border-slate-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 px-3 py-1.5 text-sm text-slate-900 dark:text-white focus:border-blue-500 focus:outline-hidden focus:ring-2 focus:ring-blue-500">
                                @foreach($this->availableClasses as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="flex gap-2 pt-1">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors text-sm font-medium">Save Changes</button>
                        <button type="button" wire:click="cancelEdit" class="px-4 py-2 bg-slate-200 dark:bg-zinc-700 text-slate-700 dark:text-slate-300 rounded-md hover:bg-slate-300 dark:hover:bg-zinc-600 transition-colors text-sm font-medium">Cancel</button>
                    </div>
                </form>
            </div>
        @endif

        {{-- Entry list header with sort + count --}}
        <div class="px-4 py-2 border-b border-zinc-700 bg-zinc-800 flex items-center justify-between">
            <h2 class="text-sm font-semibold text-white">
                Entries
                <span class="text-xs font-normal text-zinc-400">({{ $this->entryCount }})</span>
            </h2>
            <div class="flex items-center gap-1">
                <span class="text-[10px] text-zinc-500 mr-1">Sort:</span>
                <button
                    wire:click="setSortBy('name')"
                    class="px-2 py-1 text-[10px] font-medium rounded transition-colors {{ $sortBy === 'name' ? 'bg-white text-zinc-900' : 'bg-zinc-700 text-zinc-300 hover:bg-zinc-600' }}"
                >Name</button>
                <button
                    wire:click="setSortBy('car_number')"
                    class="px-2 py-1 text-[10px] font-medium rounded transition-colors {{ $sortBy === 'car_number' ? 'bg-white text-zinc-900' : 'bg-zinc-700 text-zinc-300 hover:bg-zinc-600' }}"
                >Car #</button>
                <button
                    wire:click="setSortBy('pill')"
                    class="px-2 py-1 text-[10px] font-medium rounded transition-colors {{ $sortBy === 'pill' ? 'bg-white text-zinc-900' : 'bg-zinc-700 text-zinc-300 hover:bg-zinc-600' }}"
                >Pill</button>
            </div>
        </div>

        {{-- Column headers --}}
        <div class="flex items-center bg-zinc-700 text-[10px] font-semibold text-zinc-400 uppercase tracking-wider border-b border-zinc-600 shrink-0 h-7">
            <div class="w-16 shrink-0 text-center border-r border-zinc-600">Car #</div>
            <div class="flex-1 px-3">Driver</div>
            <div class="w-24 shrink-0 text-center border-l border-zinc-600">Owner</div>
            <div class="w-32 shrink-0 text-center border-l border-zinc-600">Transponder</div>
            <div class="w-12 shrink-0 text-center border-l border-zinc-600">Pill</div>
            <div class="w-9 shrink-0 border-l border-zinc-600"></div>
            <div class="w-9 shrink-0 border-l border-zinc-600"></div>
        </div>

        {{-- Entry list --}}
        <div class="flex-1 overflow-y-auto">
            @forelse($this->entries as $entry)
                <div class="flex items-stretch border-b border-zinc-700/50 bg-zinc-800 {{ $selectedEntryId === $entry->id ? 'ring-2 ring-inset ring-blue-500' : '' }}">
                    {{-- Car Number (blocky) --}}
                    <div class="flex items-center justify-center w-16 shrink-0 bg-zinc-900 text-white font-bold text-lg border-r border-zinc-700">
                        {{ $entry->car_number }}
                    </div>
                    {{-- Driver info --}}
                    <div class="flex-1 min-w-0 px-3 py-2 flex flex-col justify-center">
                        <p class="font-medium text-white truncate text-sm">{{ $entry->driver_name }}</p>
                        @if($entry->city)
                            <p class="text-[11px] text-zinc-400 truncate">{{ $entry->city }}</p>
                        @endif
                    </div>
                    {{-- Owner (blocky) --}}
                    <div class="flex items-center justify-center w-24 shrink-0 border-l border-zinc-700 {{ $entry->owner ? 'text-zinc-200' : 'text-zinc-600' }}">
                        <span class="text-xs truncate px-1">{{ $entry->owner ?: '---' }}</span>
                    </div>
                    {{-- Transponder (blocky) --}}
                    <div class="flex items-center justify-center w-32 shrink-0 border-l border-zinc-700 {{ $entry->transponder ? 'bg-indigo-900/30 text-indigo-300' : 'text-zinc-600' }}">
                        <span class="font-mono text-[11px] truncate px-1">{{ $entry->transponder ?: '---' }}</span>
                    </div>
                    {{-- Pill (blocky) --}}
                    @if($entry->pill_number)
                        <div class="flex items-center justify-center w-12 shrink-0 bg-zinc-950 text-white font-bold text-base border-l border-zinc-700" title="Pill #{{ $entry->pill_number }}">
                            {{ $entry->pill_number }}
                        </div>
                    @else
                        <button
                            wire:click="drawPill({{ $entry->id }})"
                            class="flex flex-col items-center justify-center w-12 shrink-0 bg-zinc-700 hover:bg-zinc-600 text-zinc-400 transition-colors border-l border-zinc-700"
                            title="Draw pill number"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                            <span class="text-[8px] font-semibold mt-0.5">Draw</span>
                        </button>
                    @endif
                    {{-- Edit (blocky, full height) --}}
                    <button
                        wire:click="editEntryById({{ $entry->id }})"
                        class="flex items-center justify-center w-9 shrink-0 bg-blue-900/40 text-blue-400 hover:bg-blue-600 hover:text-white transition-colors border-l border-zinc-700"
                        title="Edit"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                    </button>
                    {{-- Delete (blocky, full height) --}}
                    <button
                        wire:click="deleteEntryById({{ $entry->id }})"
                        wire:confirm="Remove this entry?"
                        class="flex items-center justify-center w-9 shrink-0 bg-red-900/40 text-red-400 hover:bg-red-600 hover:text-white transition-colors border-l border-zinc-700"
                        title="Remove"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    </button>
                </div>
            @empty
                <div class="h-full flex flex-col items-center justify-center text-center p-8">
                    <div class="w-16 h-16 bg-zinc-700 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <p class="text-zinc-400 mb-1">No entries yet</p>
                    <p class="text-sm text-zinc-500">Search for a competitor on the left to add an entry</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
