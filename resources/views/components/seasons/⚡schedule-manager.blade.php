<?php

use App\Models\Event;
use App\Models\Schedule;
use App\Models\Season;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    public int $seasonId = 0;
    public string $search = '';
    public ?int $selectedScheduleId = null;
    public bool $isCreating = false;
    public bool $isEditing = false;

    // Form fields
    public string $formName = '';
    public int $formYear = 2026;

    #[Computed]
    public function season(): ?Season
    {
        return $this->seasonId ? Season::find($this->seasonId) : null;
    }

    #[Computed]
    public function schedules(): \Illuminate\Database\Eloquent\Collection
    {
        if (! $this->season) {
            return collect();
        }

        $query = Schedule::query()
            ->where('season_id', $this->seasonId)
            ->withCount('events')
            ->orderBy('name');

        if ($this->search) {
            $query->where('name', 'like', "%{$this->search}%");
        }

        return $query->get();
    }

    #[Computed]
    public function selectedSchedule(): ?Schedule
    {
        return $this->selectedScheduleId
            ? Schedule::with('events.track')->find($this->selectedScheduleId)
            : null;
    }

    public function selectSchedule(int $id): void
    {
        $this->selectedScheduleId = $id;
        $this->isCreating = false;
        $this->isEditing = false;
    }

    public function startCreating(): void
    {
        $this->isCreating = true;
        $this->isEditing = false;
        $this->selectedScheduleId = null;
        $this->formName = '';
        $this->formYear = $this->season?->year ?? (int) date('Y');
    }

    public function startEditing(): void
    {
        if (! $this->selectedSchedule) {
            return;
        }

        $this->isEditing = true;
        $this->isCreating = false;
        $this->formName = $this->selectedSchedule->name;
        $this->formYear = $this->selectedSchedule->year;
    }

    public function saveSchedule(): void
    {
        $this->validate([
            'formName' => ['required', 'string', 'max:255'],
            'formYear' => ['required', 'integer', 'min:2000', 'max:2100'],
        ]);

        if (! $this->season) {
            return;
        }

        if ($this->isCreating) {
            $schedule = Schedule::query()->create([
                'season_id' => $this->seasonId,
                'name' => $this->formName,
                'year' => $this->formYear,
            ]);

            $this->selectedScheduleId = $schedule->id;
        } elseif ($this->isEditing && $this->selectedSchedule) {
            $this->selectedSchedule->update([
                'name' => $this->formName,
                'year' => $this->formYear,
            ]);
        }

        $this->isCreating = false;
        $this->isEditing = false;
        unset($this->schedules, $this->selectedSchedule);
    }

    public function deleteSchedule(): void
    {
        if ($this->selectedSchedule) {
            $this->selectedSchedule->delete();
            $this->selectedScheduleId = null;
            unset($this->schedules);
        }
    }

    public function cancelForm(): void
    {
        $this->isCreating = false;
        $this->isEditing = false;
    }
}; ?>

<div class="flex flex-1 overflow-hidden">
    <!-- List Panel -->
    <div class="flex w-full max-w-md flex-col border-r border-slate-300 dark:border-zinc-700 bg-white dark:bg-zinc-800">
        <div class="bg-slate-500 dark:bg-zinc-700 p-4">
            <h2 class="mb-3 text-center text-sm font-bold tracking-widest text-white uppercase">Schedules</h2>
            <div class="relative">
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search schedules..."
                    class="w-full rounded-md border-2 border-slate-600 dark:border-zinc-600 bg-white dark:bg-zinc-800 px-3 py-2 pr-8 text-slate-900 dark:text-white placeholder-slate-400 focus:border-blue-500 focus:outline-hidden focus:ring-2 focus:ring-blue-500"
                />
            </div>
        </div>

        <div class="flex-1 overflow-y-auto">
            @if(! $this->season)
                <div class="py-8 text-center text-slate-500 dark:text-slate-400">
                    <p>Select a season first</p>
                </div>
            @else
                @forelse($this->schedules as $schedule)
                    <button
                        wire:click="selectSchedule({{ $schedule->id }})"
                        class="flex w-full items-center gap-3 px-4 py-3 border-b border-slate-200 dark:border-zinc-700 hover:bg-slate-50 dark:hover:bg-zinc-700 transition-colors text-left {{ $selectedScheduleId === $schedule->id ? 'bg-blue-50 dark:bg-zinc-700 border-l-4 border-l-blue-500' : '' }}"
                    >
                        <div class="flex items-center justify-center w-10 h-10 bg-blue-600 text-white font-bold text-xs rounded-lg shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-slate-900 dark:text-white truncate">{{ $schedule->name }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">{{ $schedule->events_count }} event(s) &middot; {{ $schedule->year }}</p>
                        </div>
                    </button>
                @empty
                    <div class="py-8 text-center text-slate-500 dark:text-slate-400">
                        <p>No schedules found</p>
                        <p class="text-sm mt-1">Create your first schedule</p>
                    </div>
                @endforelse
            @endif
        </div>

        <div class="p-3 border-t border-slate-200 dark:border-zinc-700">
            <button
                wire:click="startCreating"
                @if(! $this->season) disabled @endif
                class="flex w-full items-center justify-center gap-2 rounded-md bg-blue-600 px-4 py-3 text-sm font-medium text-white hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
            >
                New Schedule
            </button>
        </div>
    </div>

    <!-- Detail Panel -->
    <div class="flex-1 bg-slate-100 dark:bg-zinc-900 overflow-y-auto">
        @if($isCreating || $isEditing)
            <div class="p-6 max-w-lg mx-auto">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-6">
                    {{ $isCreating ? 'Create New Schedule' : 'Edit Schedule' }}
                </h3>

                <form wire:submit="saveSchedule" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Name</label>
                        <input
                            type="text"
                            wire:model="formName"
                            placeholder="e.g., Regular Season"
                            class="w-full rounded-md border border-slate-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 px-3 py-2 text-slate-900 dark:text-white focus:border-blue-500 focus:outline-hidden focus:ring-2 focus:ring-blue-500"
                        />
                        @error('formName') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Year</label>
                        <input
                            type="number"
                            wire:model="formYear"
                            min="2000"
                            max="2100"
                            class="w-full rounded-md border border-slate-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 px-3 py-2 text-slate-900 dark:text-white focus:border-blue-500 focus:outline-hidden focus:ring-2 focus:ring-blue-500"
                        />
                        @error('formYear') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex gap-3 pt-4">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors text-sm font-medium">
                            {{ $isCreating ? 'Create Schedule' : 'Save Changes' }}
                        </button>
                        <button type="button" wire:click="cancelForm" class="px-4 py-2 bg-slate-200 dark:bg-zinc-700 text-slate-700 dark:text-slate-300 rounded-md hover:bg-slate-300 dark:hover:bg-zinc-600 transition-colors text-sm font-medium">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        @elseif($this->selectedSchedule)
            <div class="p-6 max-w-2xl mx-auto">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">{{ $this->selectedSchedule->name }}</h3>
                    <div class="flex gap-2">
                        <button wire:click="startEditing" class="px-3 py-1.5 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors text-sm font-medium">Edit</button>
                        <button wire:click="deleteSchedule" wire:confirm="Are you sure? This will remove all event associations." class="px-3 py-1.5 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors text-sm font-medium">Delete</button>
                    </div>
                </div>

                <dl class="space-y-4 mb-6">
                    <div>
                        <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Year</dt>
                        <dd class="text-slate-900 dark:text-white">{{ $this->selectedSchedule->year }}</dd>
                    </div>
                </dl>

                <h4 class="text-sm font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-3">Events in this Schedule</h4>
                @if($this->selectedSchedule->events->count())
                    <div class="space-y-2">
                        @foreach($this->selectedSchedule->events->sortBy('event_date') as $event)
                            <div class="flex items-center gap-3 p-3 bg-white dark:bg-zinc-800 rounded-lg border border-slate-200 dark:border-zinc-700">
                                <div class="flex items-center justify-center w-8 h-8 bg-slate-200 dark:bg-zinc-700 rounded-lg shrink-0">
                                    <svg class="w-4 h-4 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-medium text-slate-900 dark:text-white text-sm truncate">{{ $event->name }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ $event->event_date->format('M j, Y') }} &middot; {{ $event->track?->name ?? 'No track' }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-slate-500 dark:text-slate-400">No events in this schedule yet.</p>
                @endif
            </div>
        @else
            <div class="flex h-full items-center justify-center">
                <p class="text-slate-600 dark:text-slate-400 text-center">
                    Select a schedule or
                    <button wire:click="startCreating" class="text-blue-600 dark:text-blue-400 hover:underline">create a new one</button>.
                </p>
            </div>
        @endif
    </div>
</div>
