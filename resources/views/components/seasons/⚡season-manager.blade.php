<?php

use App\Models\Sanction;
use App\Models\Season;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    public string $search = '';
    public ?int $selectedSeasonId = null;
    public bool $isCreating = false;
    public bool $isEditing = false;

    // Form fields
    public int $formYear = 2026;
    public bool $formIsActive = false;

    #[Computed]
    public function sanction(): ?Sanction
    {
        $sanctionId = session('current_sanction_id');

        return $sanctionId ? Sanction::find($sanctionId) : Sanction::first();
    }

    #[Computed]
    public function seasons(): \Illuminate\Database\Eloquent\Collection
    {
        $query = Season::query()
            ->where('sanction_id', $this->sanction?->id)
            ->orderByDesc('year');

        if ($this->search) {
            $query->where('year', 'like', "%{$this->search}%");
        }

        return $query->get();
    }

    #[Computed]
    public function selectedSeason(): ?Season
    {
        return $this->selectedSeasonId
            ? Season::find($this->selectedSeasonId)
            : null;
    }

    public function selectSeason(int $id): void
    {
        $this->selectedSeasonId = $id;
        $this->isCreating = false;
        $this->isEditing = false;
    }

    public function startCreating(): void
    {
        $this->isCreating = true;
        $this->isEditing = false;
        $this->selectedSeasonId = null;
        $this->formYear = (int) date('Y');
        $this->formIsActive = false;
    }

    public function startEditing(): void
    {
        if (! $this->selectedSeason) {
            return;
        }

        $this->isEditing = true;
        $this->isCreating = false;
        $this->formYear = $this->selectedSeason->year;
        $this->formIsActive = $this->selectedSeason->is_active;
    }

    public function saveSeason(): void
    {
        $this->validate([
            'formYear' => ['required', 'integer', 'min:2000', 'max:2100'],
        ]);

        $sanctionId = $this->sanction?->id;
        if (! $sanctionId) {
            return;
        }

        if ($this->isCreating) {
            $season = Season::query()->create([
                'sanction_id' => $sanctionId,
                'year' => $this->formYear,
                'is_active' => $this->formIsActive,
            ]);

            if ($this->formIsActive) {
                Season::query()
                    ->where('sanction_id', $sanctionId)
                    ->where('id', '!=', $season->id)
                    ->update(['is_active' => false]);
            }

            $this->selectedSeasonId = $season->id;
        } elseif ($this->isEditing && $this->selectedSeason) {
            $this->selectedSeason->update([
                'year' => $this->formYear,
                'is_active' => $this->formIsActive,
            ]);

            if ($this->formIsActive) {
                Season::query()
                    ->where('sanction_id', $sanctionId)
                    ->where('id', '!=', $this->selectedSeason->id)
                    ->update(['is_active' => false]);
            }
        }

        $this->isCreating = false;
        $this->isEditing = false;
        unset($this->seasons);
    }

    public function deleteSeason(): void
    {
        if ($this->selectedSeason) {
            $this->selectedSeason->delete();
            $this->selectedSeasonId = null;
            unset($this->seasons);
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
            <h2 class="mb-3 text-center text-sm font-bold tracking-widest text-white uppercase">Seasons</h2>
            <div class="relative">
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search by year..."
                    class="w-full rounded-md border-2 border-slate-600 dark:border-zinc-600 bg-white dark:bg-zinc-800 px-3 py-2 pr-8 text-slate-900 dark:text-white placeholder-slate-400 focus:border-blue-500 focus:outline-hidden focus:ring-2 focus:ring-blue-500"
                />
            </div>
        </div>

        <div class="flex-1 overflow-y-auto">
            @forelse($this->seasons as $season)
                <button
                    wire:click="selectSeason({{ $season->id }})"
                    class="flex w-full items-center gap-3 px-4 py-3 border-b border-slate-200 dark:border-zinc-700 hover:bg-slate-50 dark:hover:bg-zinc-700 transition-colors text-left {{ $selectedSeasonId === $season->id ? 'bg-blue-50 dark:bg-zinc-700 border-l-4 border-l-blue-500' : '' }}"
                >
                    <div class="flex items-center justify-center w-10 h-10 bg-slate-700 text-white font-bold text-sm rounded-lg shrink-0">
                        {{ $season->year }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-slate-900 dark:text-white">{{ $season->year }} Season</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">
                            {{ $season->schedules_count ?? $season->schedules()->count() }} schedule(s)
                        </p>
                    </div>
                    @if($season->is_active)
                        <span class="px-2 py-0.5 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-xs font-medium rounded-full">Active</span>
                    @endif
                </button>
            @empty
                <div class="py-8 text-center text-slate-500 dark:text-slate-400">
                    <p>No seasons found</p>
                    <p class="text-sm mt-1">Create your first season to get started</p>
                </div>
            @endforelse
        </div>

        <div class="p-3 border-t border-slate-200 dark:border-zinc-700">
            <button
                wire:click="startCreating"
                class="flex w-full items-center justify-center gap-2 rounded-md bg-blue-600 px-4 py-3 text-sm font-medium text-white hover:bg-blue-700 transition-colors"
            >
                New Season
            </button>
        </div>
    </div>

    <!-- Detail Panel -->
    <div class="flex-1 bg-slate-100 dark:bg-zinc-900 overflow-y-auto">
        @if($isCreating || $isEditing)
            <div class="p-6 max-w-lg mx-auto">
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-6">
                    {{ $isCreating ? 'Create New Season' : 'Edit Season' }}
                </h3>

                <form wire:submit="saveSeason" class="space-y-4">
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

                    <div class="flex items-center gap-2">
                        <input
                            type="checkbox"
                            wire:model="formIsActive"
                            id="is-active"
                            class="rounded border-slate-300 dark:border-zinc-600 text-blue-600 focus:ring-blue-500"
                        />
                        <label for="is-active" class="text-sm font-medium text-slate-700 dark:text-slate-300">
                            Active Season
                        </label>
                        <span class="text-xs text-slate-500 dark:text-slate-400">(only one can be active per sanction)</span>
                    </div>

                    <div class="flex gap-3 pt-4">
                        <button
                            type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors text-sm font-medium"
                        >
                            {{ $isCreating ? 'Create Season' : 'Save Changes' }}
                        </button>
                        <button
                            type="button"
                            wire:click="cancelForm"
                            class="px-4 py-2 bg-slate-200 dark:bg-zinc-700 text-slate-700 dark:text-slate-300 rounded-md hover:bg-slate-300 dark:hover:bg-zinc-600 transition-colors text-sm font-medium"
                        >
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        @elseif($this->selectedSeason)
            <div class="p-6 max-w-lg mx-auto">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">{{ $this->selectedSeason->year }} Season</h3>
                    <div class="flex gap-2">
                        <button
                            wire:click="startEditing"
                            class="px-3 py-1.5 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors text-sm font-medium"
                        >
                            Edit
                        </button>
                        <button
                            wire:click="deleteSeason"
                            wire:confirm="Are you sure you want to delete this season?"
                            class="px-3 py-1.5 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors text-sm font-medium"
                        >
                            Delete
                        </button>
                    </div>
                </div>

                <dl class="space-y-4">
                    <div>
                        <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Year</dt>
                        <dd class="text-slate-900 dark:text-white">{{ $this->selectedSeason->year }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Status</dt>
                        <dd>
                            @if($this->selectedSeason->is_active)
                                <span class="px-2 py-0.5 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-xs font-medium rounded-full">Active</span>
                            @else
                                <span class="px-2 py-0.5 bg-slate-100 dark:bg-zinc-700 text-slate-600 dark:text-slate-400 text-xs font-medium rounded-full">Inactive</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Sanction</dt>
                        <dd class="text-slate-900 dark:text-white">{{ $this->selectedSeason->sanction->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Schedules</dt>
                        <dd class="text-slate-900 dark:text-white">{{ $this->selectedSeason->schedules()->count() }}</dd>
                    </div>
                </dl>
            </div>
        @else
            <div class="flex h-full items-center justify-center">
                <p class="text-slate-600 dark:text-slate-400 text-center">
                    Select a season or
                    <button wire:click="startCreating" class="text-blue-600 dark:text-blue-400 hover:underline">create a new one</button>.
                </p>
            </div>
        @endif
    </div>
</div>
