<?php

use App\Models\Track;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    public string $search = '';
    public ?int $selectedTrackId = null;
    public bool $isCreating = false;
    public bool $isEditing = false;

    // Form fields
    public string $formName = '';
    public string $formCity = '';
    public string $formState = '';
    public string $formAddress = '';
    public string $formSurfaceType = '';
    public string $formTrackLength = '';

    #[Computed]
    public function tracks(): \Illuminate\Database\Eloquent\Collection
    {
        $query = Track::query()->orderBy('name');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('city', 'like', "%{$this->search}%")
                    ->orWhere('state', 'like', "%{$this->search}%");
            });
        }

        return $query->get();
    }

    #[Computed]
    public function selectedTrack(): ?Track
    {
        return $this->selectedTrackId ? Track::find($this->selectedTrackId) : null;
    }

    public function selectTrack(int $id): void
    {
        $this->selectedTrackId = $id;
        $this->isCreating = false;
        $this->isEditing = false;
    }

    public function startCreating(): void
    {
        $this->isCreating = true;
        $this->isEditing = false;
        $this->selectedTrackId = null;
        $this->resetFormFields();
    }

    public function startEditing(): void
    {
        if (! $this->selectedTrack) {
            return;
        }

        $this->isEditing = true;
        $this->isCreating = false;
        $this->formName = $this->selectedTrack->name;
        $this->formCity = $this->selectedTrack->city ?? '';
        $this->formState = $this->selectedTrack->state ?? '';
        $this->formAddress = $this->selectedTrack->address ?? '';
        $this->formSurfaceType = $this->selectedTrack->surface_type ?? '';
        $this->formTrackLength = $this->selectedTrack->track_length ?? '';
    }

    public function saveTrack(): void
    {
        $this->validate([
            'formName' => ['required', 'string', 'max:255'],
            'formCity' => ['required', 'string', 'max:255'],
            'formState' => ['required', 'string', 'max:255'],
        ]);

        $data = [
            'name' => $this->formName,
            'city' => $this->formCity,
            'state' => $this->formState,
            'address' => $this->formAddress ?: null,
            'surface_type' => $this->formSurfaceType ?: null,
            'track_length' => $this->formTrackLength ?: null,
        ];

        if ($this->isCreating) {
            $track = Track::query()->create($data);
            $this->selectedTrackId = $track->id;
        } elseif ($this->isEditing && $this->selectedTrack) {
            $this->selectedTrack->update($data);
        }

        $this->isCreating = false;
        $this->isEditing = false;
        unset($this->tracks, $this->selectedTrack);
    }

    public function deleteTrack(): void
    {
        if ($this->selectedTrack) {
            $this->selectedTrack->delete();
            $this->selectedTrackId = null;
            unset($this->tracks);
        }
    }

    public function cancelForm(): void
    {
        $this->isCreating = false;
        $this->isEditing = false;
    }

    private function resetFormFields(): void
    {
        $this->formName = '';
        $this->formCity = '';
        $this->formState = '';
        $this->formAddress = '';
        $this->formSurfaceType = '';
        $this->formTrackLength = '';
    }
}; ?>

<div class="flex flex-1 overflow-hidden">
    <!-- List Panel -->
    <div class="flex w-full max-w-md flex-col border-r border-slate-300 dark:border-zinc-700 bg-white dark:bg-zinc-800">
        <div class="bg-slate-500 dark:bg-zinc-700 p-4">
            <h2 class="mb-3 text-center text-sm font-bold tracking-widest text-white uppercase">Tracks</h2>
            <div class="relative">
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search tracks..."
                    class="w-full rounded-md border-2 border-slate-600 dark:border-zinc-600 bg-white dark:bg-zinc-800 px-3 py-2 pr-8 text-slate-900 dark:text-white placeholder-slate-400 focus:border-blue-500 focus:outline-hidden focus:ring-2 focus:ring-blue-500"
                />
            </div>
        </div>

        <div class="flex-1 overflow-y-auto">
            @forelse($this->tracks as $track)
                <button
                    wire:click="selectTrack({{ $track->id }})"
                    class="flex w-full items-center gap-3 px-4 py-3 border-b border-slate-200 dark:border-zinc-700 hover:bg-slate-50 dark:hover:bg-zinc-700 transition-colors text-left {{ $selectedTrackId === $track->id ? 'bg-blue-50 dark:bg-zinc-700 border-l-4 border-l-blue-500' : '' }}"
                >
                    <div class="flex items-center justify-center w-10 h-10 bg-green-600 text-white font-bold text-xs rounded-lg shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-slate-900 dark:text-white truncate">{{ $track->name }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ $track->city }}, {{ $track->state }}</p>
                    </div>
                </button>
            @empty
                <div class="py-8 text-center text-slate-500 dark:text-slate-400">
                    <p>No tracks found</p>
                    <p class="text-sm mt-1">Create your first track</p>
                </div>
            @endforelse
        </div>

        <div class="p-3 border-t border-slate-200 dark:border-zinc-700">
            <button wire:click="startCreating" class="flex w-full items-center justify-center gap-2 rounded-md bg-blue-600 px-4 py-3 text-sm font-medium text-white hover:bg-blue-700 transition-colors">
                New Track
            </button>
        </div>
    </div>

    <!-- Detail Panel -->
    <div class="flex-1 bg-slate-100 dark:bg-zinc-900 overflow-y-auto">
        @if($isCreating || $isEditing)
            <div class="p-6 max-w-lg mx-auto">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-6">
                    {{ $isCreating ? 'Create New Track' : 'Edit Track' }}
                </h3>

                <form wire:submit="saveTrack" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Track Name</label>
                        <input type="text" wire:model="formName" placeholder="e.g., Limaland Motorsports Park" class="w-full rounded-md border border-slate-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 px-3 py-2 text-slate-900 dark:text-white focus:border-blue-500 focus:outline-hidden focus:ring-2 focus:ring-blue-500" />
                        @error('formName') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">City</label>
                            <input type="text" wire:model="formCity" class="w-full rounded-md border border-slate-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 px-3 py-2 text-slate-900 dark:text-white focus:border-blue-500 focus:outline-hidden focus:ring-2 focus:ring-blue-500" />
                            @error('formCity') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">State</label>
                            <input type="text" wire:model="formState" class="w-full rounded-md border border-slate-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 px-3 py-2 text-slate-900 dark:text-white focus:border-blue-500 focus:outline-hidden focus:ring-2 focus:ring-blue-500" />
                            @error('formState') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Address</label>
                        <input type="text" wire:model="formAddress" placeholder="Optional" class="w-full rounded-md border border-slate-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 px-3 py-2 text-slate-900 dark:text-white focus:border-blue-500 focus:outline-hidden focus:ring-2 focus:ring-blue-500" />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Surface Type</label>
                            <select wire:model="formSurfaceType" class="w-full rounded-md border border-slate-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 px-3 py-2 text-slate-900 dark:text-white focus:border-blue-500 focus:outline-hidden focus:ring-2 focus:ring-blue-500">
                                <option value="">Select</option>
                                <option value="dirt">Dirt</option>
                                <option value="asphalt">Asphalt</option>
                                <option value="clay">Clay</option>
                                <option value="carpet">Carpet</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Track Length</label>
                            <input type="text" wire:model="formTrackLength" placeholder="e.g., 1/4 mile" class="w-full rounded-md border border-slate-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 px-3 py-2 text-slate-900 dark:text-white focus:border-blue-500 focus:outline-hidden focus:ring-2 focus:ring-blue-500" />
                        </div>
                    </div>

                    <div class="flex gap-3 pt-4">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors text-sm font-medium">
                            {{ $isCreating ? 'Create Track' : 'Save Changes' }}
                        </button>
                        <button type="button" wire:click="cancelForm" class="px-4 py-2 bg-slate-200 dark:bg-zinc-700 text-slate-700 dark:text-slate-300 rounded-md hover:bg-slate-300 dark:hover:bg-zinc-600 transition-colors text-sm font-medium">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        @elseif($this->selectedTrack)
            <div class="p-6 max-w-lg mx-auto">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">{{ $this->selectedTrack->name }}</h3>
                    <div class="flex gap-2">
                        <button wire:click="startEditing" class="px-3 py-1.5 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors text-sm font-medium">Edit</button>
                        <button wire:click="deleteTrack" wire:confirm="Are you sure? This will delete all events at this track." class="px-3 py-1.5 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors text-sm font-medium">Delete</button>
                    </div>
                </div>

                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Location</dt>
                        <dd class="text-slate-900 dark:text-white">{{ $this->selectedTrack->city }}, {{ $this->selectedTrack->state }}</dd>
                    </div>
                    @if($this->selectedTrack->address)
                        <div>
                            <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Address</dt>
                            <dd class="text-slate-900 dark:text-white">{{ $this->selectedTrack->address }}</dd>
                        </div>
                    @endif
                    @if($this->selectedTrack->surface_type)
                        <div>
                            <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Surface Type</dt>
                            <dd class="text-slate-900 dark:text-white capitalize">{{ $this->selectedTrack->surface_type }}</dd>
                        </div>
                    @endif
                    @if($this->selectedTrack->track_length)
                        <div>
                            <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Track Length</dt>
                            <dd class="text-slate-900 dark:text-white">{{ $this->selectedTrack->track_length }}</dd>
                        </div>
                    @endif
                </dl>
            </div>
        @else
            <div class="flex h-full items-center justify-center">
                <p class="text-slate-600 dark:text-slate-400 text-center">
                    Select a track or
                    <button wire:click="startCreating" class="text-blue-600 dark:text-blue-400 hover:underline">create a new one</button>.
                </p>
            </div>
        @endif
    </div>
</div>
