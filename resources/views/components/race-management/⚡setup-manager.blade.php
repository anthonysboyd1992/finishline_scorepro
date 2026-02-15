<?php

use App\Models\Event;
use App\Models\RaceClass;
use App\Models\RaceClassDefault;
use Livewire\Component;

new class extends Component
{
    public int $eventId = 0;
    public int $raceClassId = 0;

    // Active category: pill, transponder, race
    public string $activeCategory = 'pill';

    // Form fields
    public string $pillMode = 'none';
    public int $pillMax = 500;
    public int $pillMin = 1;
    public string $transponderMode = 'none';
    public int $competitorsPerRow = 2;

    // Add class form
    public bool $isAddingClass = false;
    public string $newClassName = '';

    // Status messages
    public string $successMessage = '';

    public function mount(): void
    {
        $this->loadSettings();
    }

    public function setCategory(string $category): void
    {
        $this->activeCategory = $category;
        $this->successMessage = '';
    }

    /**
     * Load settings from pivot, then defaults, then hardcoded fallbacks.
     */
    public function loadSettings(): void
    {
        if (! $this->eventId || ! $this->raceClassId) {
            return;
        }

        $event = Event::find($this->eventId);
        if (! $event) {
            return;
        }

        $pivot = $event->raceClasses()
            ->where('race_class_id', $this->raceClassId)
            ->first()
            ?->pivot;

        if ($pivot && $pivot->pill_mode !== null) {
            $this->pillMode = $pivot->pill_mode ?? 'none';
            $this->pillMax = $pivot->pill_max ?? 500;
            $this->pillMin = $pivot->pill_min ?? 1;
            $this->transponderMode = $pivot->transponder_mode ?? 'none';
            $this->competitorsPerRow = $pivot->competitors_per_row ?? 2;

            return;
        }

        // Try class defaults
        $defaults = RaceClassDefault::where('race_class_id', $this->raceClassId)->first();
        if ($defaults) {
            $this->pillMode = $defaults->pill_mode ?? 'none';
            $this->pillMax = $defaults->pill_max ?? 500;
            $this->pillMin = $defaults->pill_min ?? 1;
            $this->transponderMode = $defaults->transponder_mode ?? 'none';
            $this->competitorsPerRow = $defaults->competitors_per_row ?? 2;

            return;
        }

        // Hardcoded defaults
        $this->pillMode = 'none';
        $this->pillMax = 500;
        $this->pillMin = 1;
        $this->transponderMode = 'none';
        $this->competitorsPerRow = 2;
    }

    /**
     * Save settings to the event_race_class pivot table.
     */
    public function save(): void
    {
        if (! $this->eventId || ! $this->raceClassId) {
            return;
        }

        $event = Event::findOrFail($this->eventId);

        $event->raceClasses()->syncWithoutDetaching([
            $this->raceClassId => [
                'pill_mode' => $this->pillMode,
                'pill_max' => $this->pillMax,
                'pill_min' => $this->pillMin,
                'transponder_mode' => $this->transponderMode,
                'competitors_per_row' => $this->competitorsPerRow,
            ],
        ]);

        $this->successMessage = 'Settings saved successfully.';
    }

    /**
     * Save settings as default for this class (future events inherit these).
     */
    public function saveAsDefault(): void
    {
        if (! $this->raceClassId) {
            return;
        }

        RaceClassDefault::updateOrCreate(
            ['race_class_id' => $this->raceClassId],
            [
                'pill_mode' => $this->pillMode,
                'pill_max' => $this->pillMax,
                'pill_min' => $this->pillMin,
                'transponder_mode' => $this->transponderMode,
                'competitors_per_row' => $this->competitorsPerRow,
            ],
        );

        // Also save to the pivot for this event
        $this->save();

        $this->successMessage = 'Settings saved as default for this class.';
    }

    /**
     * Add a new class to the current event.
     */
    public function addClass(): void
    {
        $this->validate([
            'newClassName' => 'required|string|max:255',
        ]);

        $raceClass = RaceClass::firstOrCreate(['name' => trim($this->newClassName)]);

        $event = Event::findOrFail($this->eventId);
        if (! $event->raceClasses()->where('race_class_id', $raceClass->id)->exists()) {
            $event->raceClasses()->attach($raceClass);
        }

        $this->newClassName = '';
        $this->isAddingClass = false;

        $this->redirect(
            route('race-management.index', ['event' => $event, 'raceClass' => $raceClass]),
            navigate: true,
        );
    }

    /**
     * @return array<string, string>
     */
    public function getPillModesProperty(): array
    {
        return [
            'none' => 'No Pill Draw',
            'auto_after_confirm' => 'After Confirm Entry Auto Draw',
            'auto_before_selection' => 'Auto Draw Before Competitor Selection',
            'manual_on_confirm' => 'Manual On Confirm Entry',
            'auto_after_confirm_manual' => 'Auto Draw After Confirm',
            'after_confirm_manual_entry' => 'After Confirm Manual Entry',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function getTransponderModesProperty(): array
    {
        return [
            'none' => 'No Transponders',
            'allow_rentals' => 'Transponders Allow Rentals',
            'no_rentals' => 'Transponders No Rentals',
        ];
    }
};
?>

<div class="flex flex-1 min-h-0">
    @if($this->raceClassId)
        {{-- Left Panel: Category List --}}
        <div class="w-full md:w-72 lg:w-80 flex flex-col bg-zinc-800 border-r border-zinc-700 shrink-0">
            <div class="p-4 border-b border-zinc-700">
                <h2 class="text-lg font-semibold text-white">Event Setup</h2>
                <p class="text-sm text-zinc-400 mt-1">Configure settings for this class</p>
            </div>

            <div class="flex-1 overflow-y-auto">
                {{-- Pill Options --}}
                <button
                    wire:click="setCategory('pill')"
                    class="w-full flex items-center gap-3 px-4 py-3 border-b border-zinc-700/50 transition-colors text-left {{ $activeCategory === 'pill' ? 'bg-zinc-700 border-l-2 border-l-blue-500' : 'hover:bg-zinc-700/50' }}"
                >
                    <div class="w-10 h-10 {{ $activeCategory === 'pill' ? 'bg-blue-900/50 text-blue-400' : 'bg-zinc-700 text-zinc-400' }} rounded-lg flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-medium {{ $activeCategory === 'pill' ? 'text-white' : 'text-zinc-300' }}">Pill Options</p>
                        <p class="text-xs text-zinc-500">Pill draw configuration</p>
                    </div>
                    <svg class="w-5 h-5 text-zinc-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                {{-- Transponder Options --}}
                <button
                    wire:click="setCategory('transponder')"
                    class="w-full flex items-center gap-3 px-4 py-3 border-b border-zinc-700/50 transition-colors text-left {{ $activeCategory === 'transponder' ? 'bg-zinc-700 border-l-2 border-l-blue-500' : 'hover:bg-zinc-700/50' }}"
                >
                    <div class="w-10 h-10 {{ $activeCategory === 'transponder' ? 'bg-blue-900/50 text-blue-400' : 'bg-zinc-700 text-zinc-400' }} rounded-lg flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-medium {{ $activeCategory === 'transponder' ? 'text-white' : 'text-zinc-300' }}">Transponder Options</p>
                        <p class="text-xs text-zinc-500">Transponder requirements</p>
                    </div>
                    <svg class="w-5 h-5 text-zinc-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                {{-- Race Options --}}
                <button
                    wire:click="setCategory('race')"
                    class="w-full flex items-center gap-3 px-4 py-3 border-b border-zinc-700/50 transition-colors text-left {{ $activeCategory === 'race' ? 'bg-zinc-700 border-l-2 border-l-blue-500' : 'hover:bg-zinc-700/50' }}"
                >
                    <div class="w-10 h-10 {{ $activeCategory === 'race' ? 'bg-blue-900/50 text-blue-400' : 'bg-zinc-700 text-zinc-400' }} rounded-lg flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-medium {{ $activeCategory === 'race' ? 'text-white' : 'text-zinc-300' }}">Race Options</p>
                        <p class="text-xs text-zinc-500">Competitors per row</p>
                    </div>
                    <svg class="w-5 h-5 text-zinc-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>

            {{-- Add Class Button --}}
            <div class="shrink-0 p-3 border-t border-zinc-700">
                @if($isAddingClass)
                    <form wire:submit="addClass" class="flex items-center gap-2">
                        <input
                            type="text"
                            wire:model="newClassName"
                            placeholder="Class name"
                            autofocus
                            class="flex-1 px-3 py-1.5 bg-zinc-900 border border-zinc-600 rounded-md text-white text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none placeholder-zinc-500"
                        />
                        <button type="submit" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors">
                            Add
                        </button>
                        <button type="button" wire:click="$set('isAddingClass', false)" class="px-2 py-1.5 text-zinc-400 hover:text-zinc-200 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </form>
                    @error('newClassName')
                        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                @else
                    <button
                        wire:click="$set('isAddingClass', true)"
                        class="flex items-center justify-center gap-2 w-full px-3 py-2 bg-zinc-700 hover:bg-zinc-600 text-zinc-300 text-sm font-medium rounded-md transition-colors"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Add Class
                    </button>
                @endif
            </div>
        </div>

        {{-- Right Panel: Settings Form + Fixed Bottom Bar --}}
        <div class="flex-1 flex flex-col min-h-0">
            <div class="flex-1 bg-zinc-900 overflow-y-auto">
                <div class="max-w-2xl mx-auto p-6">
                    {{-- Success Message --}}
                    @if($successMessage)
                        <div class="mb-4 px-4 py-3 bg-green-900/30 border border-green-700/50 rounded-lg text-green-400 text-sm flex items-center gap-2">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $successMessage }}
                        </div>
                    @endif

                    {{-- Pill Options Form --}}
                    @if($activeCategory === 'pill')
                        <div>
                            <h3 class="text-lg font-semibold text-white mb-1">Pill Options</h3>
                            <p class="text-sm text-zinc-400 mb-6">Configure how pill numbers are assigned to entries.</p>

                            <div class="space-y-3">
                                @foreach($this->pillModes as $value => $label)
                                    <label class="flex items-center gap-3 p-3 rounded-lg border cursor-pointer transition-colors {{ $pillMode === $value ? 'border-blue-500 bg-blue-900/20' : 'border-zinc-700 bg-zinc-800 hover:border-zinc-600' }}">
                                        <input
                                            type="radio"
                                            wire:model.live="pillMode"
                                            value="{{ $value }}"
                                            class="w-4 h-4 text-blue-500 bg-zinc-700 border-zinc-600 focus:ring-blue-500 focus:ring-offset-0"
                                        />
                                        <span class="text-sm font-medium {{ $pillMode === $value ? 'text-white' : 'text-zinc-300' }}">{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>

                            @if($pillMode !== 'none')
                                <div class="mt-6 grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="pill-min" class="block text-sm font-medium text-zinc-300 mb-1">Lowest Pill</label>
                                        <input
                                            id="pill-min"
                                            type="number"
                                            wire:model="pillMin"
                                            min="1"
                                            class="w-full px-3 py-2 bg-zinc-800 border border-zinc-700 rounded-lg text-white text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none"
                                        />
                                    </div>
                                    <div>
                                        <label for="pill-max" class="block text-sm font-medium text-zinc-300 mb-1">Max Pill</label>
                                        <input
                                            id="pill-max"
                                            type="number"
                                            wire:model="pillMax"
                                            min="1"
                                            max="500"
                                            class="w-full px-3 py-2 bg-zinc-800 border border-zinc-700 rounded-lg text-white text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none"
                                        />
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- Transponder Options Form --}}
                    @if($activeCategory === 'transponder')
                        <div>
                            <h3 class="text-lg font-semibold text-white mb-1">Transponder Options</h3>
                            <p class="text-sm text-zinc-400 mb-6">Configure transponder requirements for entries.</p>

                            <div class="space-y-3">
                                @foreach($this->transponderModes as $value => $label)
                                    <label class="flex items-center gap-3 p-3 rounded-lg border cursor-pointer transition-colors {{ $transponderMode === $value ? 'border-blue-500 bg-blue-900/20' : 'border-zinc-700 bg-zinc-800 hover:border-zinc-600' }}">
                                        <input
                                            type="radio"
                                            wire:model.live="transponderMode"
                                            value="{{ $value }}"
                                            class="w-4 h-4 text-blue-500 bg-zinc-700 border-zinc-600 focus:ring-blue-500 focus:ring-offset-0"
                                        />
                                        <span class="text-sm font-medium {{ $transponderMode === $value ? 'text-white' : 'text-zinc-300' }}">{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Race Options Form --}}
                    @if($activeCategory === 'race')
                        <div>
                            <h3 class="text-lg font-semibold text-white mb-1">Race Options</h3>
                            <p class="text-sm text-zinc-400 mb-6">Configure race layout and competitor settings.</p>

                            <div>
                                <label for="competitors-per-row" class="block text-sm font-medium text-zinc-300 mb-1">Default Competitors Per Row</label>
                                <select
                                    id="competitors-per-row"
                                    wire:model="competitorsPerRow"
                                    class="w-full px-3 py-2 bg-zinc-800 border border-zinc-700 rounded-lg text-white text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none"
                                >
                                    @for($i = 1; $i <= 4; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                                <p class="mt-2 text-xs text-zinc-500">Qualifying and Hotlaps will always default to 1 regardless of this setting.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Fixed Bottom Toolbar --}}
            <div class="shrink-0 flex items-stretch bg-zinc-800 border-t border-zinc-700">
                @if($successMessage)
                    <span class="flex items-center gap-1.5 px-5 text-sm text-green-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        {{ $successMessage }}
                    </span>
                @endif

                <div class="ml-auto flex items-stretch">
                    <button
                        wire:click="saveAsDefault"
                        wire:loading.attr="disabled"
                        class="px-5 py-3 bg-zinc-700 hover:bg-zinc-600 text-zinc-200 text-sm font-medium transition-colors disabled:opacity-50 border-l border-zinc-700"
                    >
                        <span wire:loading.remove wire:target="saveAsDefault">Save as Default</span>
                        <span wire:loading wire:target="saveAsDefault">Saving...</span>
                    </button>
                    <button
                        wire:click="save"
                        wire:loading.attr="disabled"
                        class="px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium transition-colors disabled:opacity-50"
                    >
                        <span wire:loading.remove wire:target="save">Save</span>
                        <span wire:loading wire:target="save">Saving...</span>
                    </button>
                </div>
            </div>
        </div>
    @else
        {{-- No class selected --}}
        <div class="flex-1 bg-zinc-900 flex items-center justify-center">
            <div class="text-center p-8">
                <div class="w-16 h-16 bg-zinc-700 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                </div>
                <p class="text-zinc-400 mb-2">No classes for this event</p>
                <p class="text-sm text-zinc-500 mb-4">Add a class to get started with setup</p>

                @if($isAddingClass)
                    <form wire:submit="addClass" class="flex items-center gap-2 max-w-xs mx-auto">
                        <input
                            type="text"
                            wire:model="newClassName"
                            placeholder="Class name"
                            autofocus
                            class="flex-1 px-3 py-2 bg-zinc-800 border border-zinc-600 rounded-md text-white text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 focus:outline-none placeholder-zinc-500"
                        />
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors">
                            Add
                        </button>
                        <button type="button" wire:click="$set('isAddingClass', false)" class="px-2 py-2 text-zinc-400 hover:text-zinc-200 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </form>
                    @error('newClassName')
                        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                @else
                    <button
                        wire:click="$set('isAddingClass', true)"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Add Class
                    </button>
                @endif
            </div>
        </div>
    @endif
</div>
