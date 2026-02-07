<?php
$ctx = new \App\Support\RaceManagementContext(
    event: $event,
    raceClass: $raceClass,
    currentPage: 'entries',
);

// Example entries data
$entries = [
    ['id' => 1, 'number' => '01', 'driver' => 'Mike Johnson', 'class' => 'Stock Novice', 'transponder' => 'A01', 'car' => 'TLR 22 5.0'],
    ['id' => 2, 'number' => '07', 'driver' => 'Sarah Williams', 'class' => 'Stock Novice', 'transponder' => 'A07', 'car' => 'Associated B6.4'],
    ['id' => 3, 'number' => '12', 'driver' => 'Tom Davis', 'class' => 'Stock Sportsman', 'transponder' => 'A12', 'car' => 'Yokomo YZ-2'],
    ['id' => 4, 'number' => '23', 'driver' => 'Chris Martinez', 'class' => 'Modified', 'transponder' => 'A23', 'car' => 'Schumacher CAT L1'],
    ['id' => 5, 'number' => '44', 'driver' => 'Emily Brown', 'class' => '17.5 Blinky', 'transponder' => 'A44', 'car' => 'Xray XB4'],
    ['id' => 6, 'number' => '55', 'driver' => 'David Wilson', 'class' => 'Open Modified', 'transponder' => 'A55', 'car' => 'Kyosho RB7'],
    ['id' => 7, 'number' => '88', 'driver' => 'Jessica Lee', 'class' => 'Stock Novice', 'transponder' => 'A88', 'car' => 'Serpent SRX8'],
];
$entryCount = count($entries);
?>

<x-layouts::module
    title="Entries"
    moduleTitle="RACE MANAGEMENT"
    :sidebarItems="$ctx->sidebarItems()"
    currentItem="entries"
    :showContextBar="true"
    :events="$ctx->eventsForDropdown()"
    :currentEvent="$ctx->currentEventArray()"
    :classes="$ctx->classesForDropdown()"
    :currentClass="$ctx->currentClassArray()"
    :entryCount="$entryCount"
>
    <!-- List Panel - Entry List -->
    <div class="w-full md:w-96 lg:w-[420px] flex flex-col bg-white dark:bg-zinc-800 border-r border-slate-200 dark:border-zinc-700 shrink-0">
        <!-- Header -->
        <div class="p-4 border-b border-slate-200 dark:border-zinc-700">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Entries</h2>
                <button class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Entry
                </button>
            </div>
            <!-- Search -->
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input 
                    type="text" 
                    placeholder="Search by name, number, or transponder..."
                    class="w-full pl-10 pr-4 py-2 border border-slate-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-slate-900 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 text-sm focus:outline-hidden focus:ring-2 focus:ring-red-500 focus:border-transparent"
                />
            </div>
        </div>

        <!-- Entry List -->
        <div class="flex-1 overflow-y-auto">
            @foreach($entries as $entry)
                <a href="#" class="flex items-center gap-3 px-4 py-3 border-b border-slate-100 dark:border-zinc-700/50 hover:bg-slate-50 dark:hover:bg-zinc-700/50 transition-colors">
                    <!-- Entry Number -->
                    <div class="flex items-center justify-center w-10 h-10 bg-red-600 text-white font-bold text-lg rounded-lg shrink-0">
                        {{ $entry['number'] }}
                    </div>
                    
                    <!-- Entry Info -->
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-slate-900 dark:text-white truncate">{{ $entry['driver'] }}</p>
                        <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                            <span class="px-1.5 py-0.5 bg-sky-100 dark:bg-sky-900/30 text-sky-700 dark:text-sky-400 rounded">{{ $entry['class'] }}</span>
                            <span class="font-mono">{{ $entry['transponder'] }}</span>
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
        <!-- Empty State -->
        <div class="h-full flex flex-col items-center justify-center text-center p-8">
            <div class="w-16 h-16 bg-slate-200 dark:bg-zinc-700 rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <p class="text-slate-600 dark:text-slate-400 mb-2">Select an entry to view details</p>
            <p class="text-sm text-slate-500 dark:text-slate-500">
                or <a href="#" class="text-red-600 dark:text-red-400 hover:underline">add a new entry</a>
            </p>
        </div>
    </div>
</x-layouts::module>
