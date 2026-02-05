<?php
$sidebarItems = [
    [
        'key' => 'setup',
        'label' => 'Setup',
        'href' => route('races.index'),
        'wire' => true,
        'icon' => '<svg class="w-6 h-6 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>',
    ],
    [
        'key' => 'entries',
        'label' => 'Entries',
        'href' => route('races.entries'),
        'wire' => true,
        'icon' => '<svg class="w-6 h-6 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>',
    ],
    [
        'key' => 'races',
        'label' => 'Races',
        'href' => route('races.races'),
        'wire' => true,
        'icon' => '<svg class="w-6 h-6 md:w-8 md:h-8" viewBox="0 0 24 24" fill="currentColor"><path d="M4 4v16h2V4H4zm4 0v4h2v4h2V8h2v4h2V8h2v4h2V4H8zm0 8v4h2v-4H8zm4 0v4h2v-4h-2zm4 0v4h2v-4h-2zm-8 4v4h2v-4H8zm4 0v4h2v-4h-2zm4 0v4h2v-4h-2z"/></svg>',
    ],
    [
        'key' => 'race-order',
        'label' => 'Race Order',
        'href' => route('races.race-order'),
        'wire' => true,
        'icon' => '<svg class="w-6 h-6 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" /></svg>',
    ],
    [
        'key' => 'lineups',
        'label' => 'Lineups',
        'href' => route('races.lineups'),
        'wire' => true,
        'icon' => '<svg class="w-6 h-6 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>',
    ],
    [
        'key' => 'scoring',
        'label' => 'Scoring',
        'href' => route('races.scoring'),
        'wire' => true,
        'icon' => '<svg class="w-6 h-6 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>',
    ],
];

$events = [
    ['id' => 1, 'name' => 'Saturday Racing', 'date' => 'Mar 15, 2026', 'href' => '#'],
    ['id' => 2, 'name' => 'Sunday Finals', 'date' => 'Mar 16, 2026', 'href' => '#'],
    ['id' => 3, 'name' => 'Practice Day', 'date' => 'Mar 14, 2026', 'href' => '#'],
];
$currentEvent = $events[0];

$classes = [
    ['id' => 1, 'name' => 'Stock Novice', 'href' => '#'],
    ['id' => 2, 'name' => 'Stock Sportsman', 'href' => '#'],
    ['id' => 3, 'name' => 'Modified', 'href' => '#'],
    ['id' => 4, 'name' => '17.5 Blinky', 'href' => '#'],
    ['id' => 5, 'name' => 'Open Modified', 'href' => '#'],
];
$currentClass = null;

// Example lineup data for a race
$currentRace = ['name' => 'Stock Novice Q1', 'class' => 'Stock Novice'];
$lineup = [
    ['position' => 1, 'number' => '07', 'driver' => 'Sarah Williams', 'transponder' => 'A07', 'qualifier' => '1st'],
    ['position' => 2, 'number' => '01', 'driver' => 'Mike Johnson', 'transponder' => 'A01', 'qualifier' => '2nd'],
    ['position' => 3, 'number' => '88', 'driver' => 'Jessica Lee', 'transponder' => 'A88', 'qualifier' => '3rd'],
    ['position' => 4, 'number' => '15', 'driver' => 'Alex Turner', 'transponder' => 'A15', 'qualifier' => '4th'],
    ['position' => 5, 'number' => '33', 'driver' => 'Ryan Cooper', 'transponder' => 'A33', 'qualifier' => '5th'],
    ['position' => 6, 'number' => '42', 'driver' => 'Nicole Adams', 'transponder' => 'A42', 'qualifier' => '6th'],
    ['position' => 7, 'number' => '67', 'driver' => 'Brandon Hill', 'transponder' => 'A67', 'qualifier' => '7th'],
    ['position' => 8, 'number' => '99', 'driver' => 'Amanda Scott', 'transponder' => 'A99', 'qualifier' => '8th'],
];

$availableRaces = [
    ['id' => 1, 'name' => 'Stock Novice Q1', 'entries' => 8],
    ['id' => 2, 'name' => 'Stock Novice Q2', 'entries' => 8],
    ['id' => 3, 'name' => 'Stock Sportsman Q1', 'entries' => 6],
    ['id' => 4, 'name' => 'Modified Q1', 'entries' => 5],
];
?>

<x-layouts::module 
    title="Lineups"
    moduleTitle="RACES"
    :sidebarItems="$sidebarItems"
    currentItem="lineups"
    :showContextBar="true"
    :events="$events"
    :currentEvent="$currentEvent"
    :classes="$classes"
    :currentClass="$currentClass"
>
    <!-- List Panel - Race Selection -->
    <div class="w-full md:w-72 lg:w-80 flex flex-col bg-white dark:bg-zinc-800 border-r border-slate-200 dark:border-zinc-700 shrink-0">
        <!-- Header -->
        <div class="p-4 border-b border-slate-200 dark:border-zinc-700">
            <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Select Race</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Choose a race to view lineup</p>
        </div>

        <!-- Race List -->
        <div class="flex-1 overflow-y-auto">
            @foreach($availableRaces as $race)
                <a href="#" class="flex items-center gap-3 px-4 py-3 border-b border-slate-100 dark:border-zinc-700/50 hover:bg-slate-50 dark:hover:bg-zinc-700/50 transition-colors {{ $loop->first ? 'bg-red-50 dark:bg-red-900/20 border-l-4 border-l-red-500' : '' }}">
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-slate-900 dark:text-white truncate">{{ $race['name'] }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ $race['entries'] }} entries</p>
                    </div>
                    <svg class="w-5 h-5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            @endforeach
        </div>
    </div>

    <!-- Lineup Panel -->
    <div class="flex-1 bg-slate-100 dark:bg-zinc-900 overflow-y-auto">
        <!-- Lineup Header -->
        <div class="bg-white dark:bg-zinc-800 border-b border-slate-200 dark:border-zinc-700 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">{{ $currentRace['name'] }}</h3>
                    <span class="text-xs px-1.5 py-0.5 bg-sky-100 dark:bg-sky-900/30 text-sky-700 dark:text-sky-400 rounded">{{ $currentRace['class'] }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <button class="inline-flex items-center gap-1.5 px-3 py-1.5 border border-slate-300 dark:border-zinc-600 text-slate-700 dark:text-slate-300 text-sm font-medium rounded-md hover:bg-slate-50 dark:hover:bg-zinc-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Print
                    </button>
                    <button class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Start Race
                    </button>
                </div>
            </div>
        </div>

        <!-- Lineup Grid -->
        <div class="p-4">
            <div class="bg-white dark:bg-zinc-800 rounded-xl border border-slate-200 dark:border-zinc-700 overflow-hidden">
                <!-- Table Header -->
                <div class="grid grid-cols-12 gap-4 px-4 py-3 bg-slate-50 dark:bg-zinc-700/50 border-b border-slate-200 dark:border-zinc-700 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                    <div class="col-span-1">Pos</div>
                    <div class="col-span-2">No.</div>
                    <div class="col-span-4">Driver</div>
                    <div class="col-span-2">Transponder</div>
                    <div class="col-span-2">Qualifier</div>
                    <div class="col-span-1"></div>
                </div>

                <!-- Lineup Rows -->
                @foreach($lineup as $entry)
                    <div class="grid grid-cols-12 gap-4 px-4 py-3 border-b border-slate-100 dark:border-zinc-700/50 hover:bg-slate-50 dark:hover:bg-zinc-700/30 transition-colors items-center">
                        <div class="col-span-1">
                            <span class="w-7 h-7 bg-zinc-800 dark:bg-zinc-900 text-white font-bold text-sm rounded flex items-center justify-center">
                                {{ $entry['position'] }}
                            </span>
                        </div>
                        <div class="col-span-2">
                            <span class="px-2 py-1 bg-red-600 text-white font-bold text-sm rounded">
                                {{ $entry['number'] }}
                            </span>
                        </div>
                        <div class="col-span-4 font-medium text-slate-900 dark:text-white">
                            {{ $entry['driver'] }}
                        </div>
                        <div class="col-span-2 font-mono text-sm text-slate-600 dark:text-slate-400">
                            {{ $entry['transponder'] }}
                        </div>
                        <div class="col-span-2 text-sm text-slate-600 dark:text-slate-400">
                            {{ $entry['qualifier'] }}
                        </div>
                        <div class="col-span-1 text-right">
                            <button class="p-1 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-layouts::module>
