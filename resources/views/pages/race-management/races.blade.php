<?php
$ctx = new \App\Support\RaceManagementContext(
    event: $event,
    raceClass: $raceClass,
    currentPage: 'races',
);

// Example races data
$races = [
    ['id' => 1, 'name' => 'Stock Novice Q1', 'class' => 'Stock Novice', 'status' => 'completed', 'entries' => 8],
    ['id' => 2, 'name' => 'Stock Novice Q2', 'class' => 'Stock Novice', 'status' => 'completed', 'entries' => 8],
    ['id' => 3, 'name' => 'Stock Sportsman Q1', 'class' => 'Stock Sportsman', 'status' => 'running', 'entries' => 6],
    ['id' => 4, 'name' => 'Stock Sportsman Q2', 'class' => 'Stock Sportsman', 'status' => 'upcoming', 'entries' => 6],
    ['id' => 5, 'name' => 'Modified Q1', 'class' => 'Modified', 'status' => 'upcoming', 'entries' => 5],
    ['id' => 6, 'name' => '17.5 Blinky Q1', 'class' => '17.5 Blinky', 'status' => 'upcoming', 'entries' => 4],
];
?>

<x-layouts::module
    title="Races"
    moduleTitle="RACE MANAGEMENT"
    :sidebarItems="$ctx->sidebarItems()"
    :seasons="$ctx->seasonsForDropdown()"
    :currentSeason="$ctx->currentSeasonArray()"
    currentItem="races"
    :showContextBar="true"
    :events="$ctx->eventsForDropdown()"
    :currentEvent="$ctx->currentEventArray()"
    :classes="$ctx->classesForDropdown()"
    :currentClass="$ctx->currentClassArray()"
>
    <!-- List Panel - Race List -->
    <div class="w-full md:w-96 lg:w-[420px] flex flex-col bg-white dark:bg-zinc-800 border-r border-slate-200 dark:border-zinc-700 shrink-0">
        <!-- Header -->
        <div class="p-4 border-b border-slate-200 dark:border-zinc-700">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Races</h2>
                <button class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    New Race
                </button>
            </div>
            <!-- Search -->
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input 
                    type="text" 
                    placeholder="Search races..."
                    class="w-full pl-10 pr-4 py-2 border border-slate-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 text-sm focus:outline-hidden focus:ring-2 focus:ring-red-500 focus:border-transparent"
                />
            </div>
        </div>

        <!-- Race List -->
        <div class="flex-1 overflow-y-auto">
            @foreach($races as $race)
                <a href="#" class="flex items-center gap-3 px-4 py-3 border-b border-slate-100 dark:border-zinc-700/50 hover:bg-slate-50 dark:hover:bg-zinc-700/50 transition-colors">
                    <!-- Status Indicator -->
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center shrink-0
                        @if($race['status'] === 'completed') bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400
                        @elseif($race['status'] === 'running') bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400
                        @else bg-slate-100 dark:bg-zinc-700 text-slate-500 dark:text-slate-400 @endif">
                        @if($race['status'] === 'completed')
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        @elseif($race['status'] === 'running')
                            <svg class="w-5 h-5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        @else
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        @endif
                    </div>
                    
                    <!-- Race Info -->
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-slate-900 dark:text-white truncate">{{ $race['name'] }}</p>
                        <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                            <span class="px-1.5 py-0.5 bg-sky-100 dark:bg-sky-900/30 text-sky-700 dark:text-sky-400 rounded">{{ $race['class'] }}</span>
                            <span>{{ $race['entries'] }} entries</span>
                        </div>
                    </div>

                    <!-- Chevron -->
                    <svg class="w-5 h-5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            @endforeach
        </div>
    </div>

    <!-- Detail Panel -->
    <div class="flex-1 bg-slate-100 dark:bg-zinc-900 overflow-y-auto">
        <div class="h-full flex flex-col items-center justify-center text-center p-8">
            <div class="w-16 h-16 bg-slate-200 dark:bg-zinc-700 rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-slate-400 dark:text-slate-500" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M4 4v16h2V4H4zm4 0v4h2v4h2V8h2v4h2V8h2v4h2V4H8zm0 8v4h2v-4H8zm4 0v4h2v-4h-2zm4 0v4h2v-4h-2zm-8 4v4h2v-4H8zm4 0v4h2v-4h-2zm4 0v4h2v-4h-2z"/>
                </svg>
            </div>
            <p class="text-slate-600 dark:text-slate-400 mb-2">Select a race to view details</p>
            <p class="text-sm text-slate-500 dark:text-slate-500">
                or <a href="#" class="text-red-600 dark:text-red-400 hover:underline">create a new race</a>
            </p>
        </div>
    </div>
</x-layouts::module>
