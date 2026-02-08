<?php

use App\Models\Event;
use App\Models\RaceClass;
use App\Models\Schedule;
use App\Models\Season;
use App\Models\Track;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    public int $seasonId = 0;
    public string $search = '';
    public ?int $selectedEventId = null;
    public bool $isCreating = false;
    public bool $isEditing = false;

    // Form fields
    public string $formName = '';
    public string $formDate = '';
    public ?int $formTrackId = null;
    public ?int $formScheduleId = null;
    public array $formClassIds = [];

    #[Computed]
    public function season(): ?Season
    {
        return $this->seasonId ? Season::with('schedules')->find($this->seasonId) : null;
    }

    #[Computed]
    public function events(): \Illuminate\Support\Collection
    {
        if (! $this->season) {
            return collect();
        }

        $scheduleIds = $this->season->schedules->pluck('id');

        $query = Event::query()
            ->whereHas('schedules', fn ($q) => $q->whereIn('schedules.id', $scheduleIds))
            ->with(['track', 'raceClasses'])
            ->orderBy('event_date');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhereHas('track', fn ($tq) => $tq->where('name', 'like', "%{$this->search}%"));
            });
        }

        return $query->get();
    }

    #[Computed]
    public function selectedEvent(): ?Event
    {
        return $this->selectedEventId
            ? Event::with(['track', 'raceClasses', 'schedules'])->find($this->selectedEventId)
            : null;
    }

    #[Computed]
    public function tracks(): \Illuminate\Database\Eloquent\Collection
    {
        return Track::query()->orderBy('name')->get();
    }

    #[Computed]
    public function allClasses(): \Illuminate\Database\Eloquent\Collection
    {
        return RaceClass::query()->orderBy('name')->get();
    }

    public function selectEvent(int $id): void
    {
        $this->selectedEventId = $id;
        $this->isCreating = false;
        $this->isEditing = false;
    }

    public function startCreating(): void
    {
        $this->isCreating = true;
        $this->isEditing = false;
        $this->selectedEventId = null;
        $this->formName = '';
        $this->formDate = date('Y-m-d');
        $this->formTrackId = null;
        $this->formScheduleId = $this->season?->schedules->first()?->id;
        $this->formClassIds = [];
    }

    public function startEditing(): void
    {
        if (! $this->selectedEvent) {
            return;
        }

        $this->isEditing = true;
        $this->isCreating = false;
        $this->formName = $this->selectedEvent->name ?? '';
        $this->formDate = $this->selectedEvent->event_date->format('Y-m-d');
        $this->formTrackId = $this->selectedEvent->track_id;
        $this->formClassIds = $this->selectedEvent->raceClasses->pluck('id')->all();
    }

    public function saveEvent(): void
    {
        $this->validate([
            'formName' => ['nullable', 'string', 'max:255'],
            'formDate' => ['required', 'date'],
            'formTrackId' => ['required', 'exists:tracks,id'],
        ]);

        if ($this->isCreating) {
            $event = Event::query()->create([
                'name' => $this->formName ?: null,
                'event_date' => $this->formDate,
                'track_id' => $this->formTrackId,
            ]);

            // Attach to schedule
            if ($this->formScheduleId) {
                $event->schedules()->attach($this->formScheduleId);
            }

            // Attach classes
            if (! empty($this->formClassIds)) {
                $event->raceClasses()->sync($this->formClassIds);
            }

            $this->selectedEventId = $event->id;
        } elseif ($this->isEditing && $this->selectedEvent) {
            $this->selectedEvent->update([
                'name' => $this->formName ?: null,
                'event_date' => $this->formDate,
                'track_id' => $this->formTrackId,
            ]);

            if (! empty($this->formClassIds)) {
                $this->selectedEvent->raceClasses()->sync($this->formClassIds);
            }
        }

        $this->isCreating = false;
        $this->isEditing = false;
        unset($this->events, $this->selectedEvent);
    }

    public function deleteEvent(): void
    {
        if ($this->selectedEvent) {
            $this->selectedEvent->delete();
            $this->selectedEventId = null;
            unset($this->events);
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
            <h2 class="mb-3 text-center text-sm font-bold tracking-widest text-white uppercase">Events</h2>
            <div class="relative">
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search events..."
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
                @forelse($this->events as $event)
                    <button
                        wire:click="selectEvent({{ $event->id }})"
                        class="flex w-full items-center gap-3 px-4 py-3 border-b border-slate-200 dark:border-zinc-700 hover:bg-slate-50 dark:hover:bg-zinc-700 transition-colors text-left {{ $selectedEventId === $event->id ? 'bg-blue-50 dark:bg-zinc-700 border-l-4 border-l-blue-500' : '' }}"
                    >
                        <div class="flex items-center justify-center w-10 h-10 bg-orange-600 text-white font-bold text-xs rounded-lg shrink-0">
                            {{ $event->event_date->format('M') }}<br>{{ $event->event_date->format('d') }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-slate-900 dark:text-white truncate">{{ $event->displayName() }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">{{ $event->track?->name }}</p>
                        </div>
                    </button>
                @empty
                    <div class="py-8 text-center text-slate-500 dark:text-slate-400">
                        <p>No events found</p>
                        <p class="text-sm mt-1">Create your first event</p>
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
                New Event
            </button>
        </div>
    </div>

    <!-- Detail Panel -->
    <div class="flex-1 bg-slate-100 dark:bg-zinc-900 overflow-y-auto">
        @if($isCreating || $isEditing)
            <div class="p-6 max-w-lg mx-auto">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-6">
                    {{ $isCreating ? 'Create New Event' : 'Edit Event' }}
                </h3>

                <form wire:submit="saveEvent" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Event Name</label>
                        <input type="text" wire:model="formName" placeholder="e.g., Saturday Racing" class="w-full rounded-md border border-slate-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 px-3 py-2 text-slate-900 dark:text-white focus:border-blue-500 focus:outline-hidden focus:ring-2 focus:ring-blue-500" />
                        @error('formName') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Date</label>
                        <input type="date" wire:model="formDate" class="w-full rounded-md border border-slate-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 px-3 py-2 text-slate-900 dark:text-white focus:border-blue-500 focus:outline-hidden focus:ring-2 focus:ring-blue-500" />
                        @error('formDate') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Track</label>
                        <select wire:model="formTrackId" class="w-full rounded-md border border-slate-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 px-3 py-2 text-slate-900 dark:text-white focus:border-blue-500 focus:outline-hidden focus:ring-2 focus:ring-blue-500">
                            <option value="">Select a track</option>
                            @foreach($this->tracks as $track)
                                <option value="{{ $track->id }}">{{ $track->name }} ({{ $track->city }}, {{ $track->state }})</option>
                            @endforeach
                        </select>
                        @error('formTrackId') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    @if($isCreating && $this->season?->schedules->count())
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Add to Schedule</label>
                            <select wire:model="formScheduleId" class="w-full rounded-md border border-slate-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 px-3 py-2 text-slate-900 dark:text-white focus:border-blue-500 focus:outline-hidden focus:ring-2 focus:ring-blue-500">
                                @foreach($this->season->schedules as $schedule)
                                    <option value="{{ $schedule->id }}">{{ $schedule->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Classes</label>
                        <div class="space-y-2 max-h-48 overflow-y-auto p-3 bg-white dark:bg-zinc-800 border border-slate-300 dark:border-zinc-600 rounded-md">
                            @foreach($this->allClasses as $class)
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" wire:model="formClassIds" value="{{ $class->id }}" class="rounded border-slate-300 dark:border-zinc-600 text-blue-600 focus:ring-blue-500" />
                                    <span class="text-sm text-slate-700 dark:text-slate-300">{{ $class->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex gap-3 pt-4">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors text-sm font-medium">
                            {{ $isCreating ? 'Create Event' : 'Save Changes' }}
                        </button>
                        <button type="button" wire:click="cancelForm" class="px-4 py-2 bg-slate-200 dark:bg-zinc-700 text-slate-700 dark:text-slate-300 rounded-md hover:bg-slate-300 dark:hover:bg-zinc-600 transition-colors text-sm font-medium">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        @elseif($this->selectedEvent)
            <div class="p-6 max-w-2xl mx-auto">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">{{ $this->selectedEvent->displayName() }}</h3>
                    <div class="flex gap-2">
                        <button wire:click="startEditing" class="px-3 py-1.5 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors text-sm font-medium">Edit</button>
                        <button wire:click="deleteEvent" wire:confirm="Are you sure you want to delete this event?" class="px-3 py-1.5 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors text-sm font-medium">Delete</button>
                    </div>
                </div>

                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Date</dt>
                        <dd class="text-slate-900 dark:text-white">{{ $this->selectedEvent->event_date->format('F j, Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Track</dt>
                        <dd class="text-slate-900 dark:text-white">{{ $this->selectedEvent->track?->name ?? 'None' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Classes</dt>
                        <dd class="flex flex-wrap gap-2 mt-1">
                            @forelse($this->selectedEvent->raceClasses as $class)
                                <span class="px-2 py-1 bg-sky-100 dark:bg-sky-900/30 text-sky-700 dark:text-sky-400 text-xs font-medium rounded">{{ $class->name }}</span>
                            @empty
                                <span class="text-sm text-slate-500 dark:text-slate-400">No classes assigned</span>
                            @endforelse
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Schedules</dt>
                        <dd class="flex flex-wrap gap-2 mt-1">
                            @forelse($this->selectedEvent->schedules as $schedule)
                                <span class="px-2 py-1 bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400 text-xs font-medium rounded">{{ $schedule->name }}</span>
                            @empty
                                <span class="text-sm text-slate-500 dark:text-slate-400">Not in any schedule</span>
                            @endforelse
                        </dd>
                    </div>
                </dl>
            </div>
        @else
            <div class="flex h-full items-center justify-center">
                <p class="text-slate-600 dark:text-slate-400 text-center">
                    Select an event or
                    <button wire:click="startCreating" class="text-blue-600 dark:text-blue-400 hover:underline">create a new one</button>.
                </p>
            </div>
        @endif
    </div>
</div>
