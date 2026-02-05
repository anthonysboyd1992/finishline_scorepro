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

// Example race order data
$raceOrder = [
    ['position' => 1, 'name' => 'Stock Novice Q1', 'class' => 'Stock Novice', 'status' => 'completed'],
    ['position' => 2, 'name' => 'Stock Sportsman Q1', 'class' => 'Stock Sportsman', 'status' => 'completed'],
    ['position' => 3, 'name' => 'Modified Q1', 'class' => 'Modified', 'status' => 'running'],
    ['position' => 4, 'name' => '17.5 Blinky Q1', 'class' => '17.5 Blinky', 'status' => 'upcoming'],
    ['position' => 5, 'name' => 'Open Modified Q1', 'class' => 'Open Modified', 'status' => 'upcoming'],
    ['position' => 6, 'name' => 'Stock Novice Q2', 'class' => 'Stock Novice', 'status' => 'upcoming'],
    ['position' => 7, 'name' => 'Stock Sportsman Q2', 'class' => 'Stock Sportsman', 'status' => 'upcoming'],
    ['position' => 8, 'name' => 'Modified Q2', 'class' => 'Modified', 'status' => 'upcoming'],
];
?>

<x-layouts::module 
    title="Race Order"
    moduleTitle="RACES"
    :sidebarItems="$sidebarItems"
    currentItem="race-order"
    :showContextBar="true"
    :events="$events"
    :currentEvent="$currentEvent"
    :classes="$classes"
    :currentClass="$currentClass"
>
    <!-- Full Width Content -->
    <div class="flex-1 bg-white dark:bg-zinc-800 overflow-y-auto">
        <!-- Header -->
        <div class="p-4 border-b border-slate-200 dark:border-zinc-700">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Race Order</h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Drag and drop to reorder races</p>
                </div>
                <div class="flex items-center gap-2">
                    <button class="inline-flex items-center gap-1.5 px-3 py-1.5 border border-slate-300 dark:border-zinc-600 text-slate-700 dark:text-slate-300 text-sm font-medium rounded-md hover:bg-slate-50 dark:hover:bg-zinc-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Auto-Generate
                    </button>
                    <button class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Add Race
                    </button>
                </div>
            </div>
        </div>

        <!-- Race Order List -->
        <div class="p-4">
            <div class="space-y-2">
                @foreach($raceOrder as $race)
                    <div class="flex items-center gap-3 p-3 bg-slate-50 dark:bg-zinc-700/50 rounded-lg border border-slate-200 dark:border-zinc-600 hover:border-red-300 dark:hover:border-red-600 transition-colors cursor-move">
                        <!-- Drag Handle -->
                        <div class="text-slate-400 dark:text-slate-500 cursor-move">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
                            </svg>
                        </div>

                        <!-- Position -->
                        <div class="w-8 h-8 bg-zinc-800 dark:bg-zinc-900 text-white font-bold text-sm rounded flex items-center justify-center shrink-0">
                            {{ $race['position'] }}
                        </div>

                        <!-- Race Info -->
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-slate-900 dark:text-white">{{ $race['name'] }}</p>
                            <span class="text-xs px-1.5 py-0.5 bg-sky-100 dark:bg-sky-900/30 text-sky-700 dark:text-sky-400 rounded">{{ $race['class'] }}</span>
                        </div>

                        <!-- Status -->
                        <div class="shrink-0">
                            @if($race['status'] === 'completed')
                                <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-full">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    Done
                                </span>
                            @elseif($race['status'] === 'running')
                                <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 rounded-full animate-pulse">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                                    </svg>
                                    Running
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium bg-slate-100 dark:bg-zinc-600 text-slate-600 dark:text-slate-300 rounded-full">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Upcoming
                                </span>
                            @endif
                        </div>

                        <!-- Actions -->
                        <button class="p-1 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                            </svg>
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-layouts::module>
