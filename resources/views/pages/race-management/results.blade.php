<?php
$sidebarItems = [
    [
        'key' => 'setup',
        'label' => 'Setup',
        'href' => route('race-management.index'),
        'wire' => true,
        'icon' => '<svg class="w-6 h-6 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>',
    ],
    [
        'key' => 'entries',
        'label' => 'Entries',
        'href' => route('race-management.entries'),
        'wire' => true,
        'icon' => '<svg class="w-6 h-6 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>',
    ],
    [
        'key' => 'races',
        'label' => 'Races',
        'href' => route('race-management.races'),
        'wire' => true,
        'icon' => '<svg class="w-6 h-6 md:w-8 md:h-8" viewBox="0 0 24 24" fill="currentColor"><path d="M4 4v16h2V4H4zm4 0v4h2v4h2V8h2v4h2V8h2v4h2V4H8zm0 8v4h2v-4H8zm4 0v4h2v-4h-2zm4 0v4h2v-4h-2zm-8 4v4h2v-4H8zm4 0v4h2v-4h-2zm4 0v4h2v-4h-2z"/></svg>',
    ],
    [
        'key' => 'race-order',
        'label' => 'Race Order',
        'href' => route('race-management.race-order'),
        'wire' => true,
        'icon' => '<svg class="w-6 h-6 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" /></svg>',
    ],
    [
        'key' => 'lineups',
        'label' => 'Lineups',
        'href' => route('race-management.lineups'),
        'wire' => true,
        'icon' => '<svg class="w-6 h-6 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>',
    ],
    [
        'key' => 'results',
        'label' => 'Results',
        'href' => route('race-management.results'),
        'wire' => true,
        'icon' => '<svg class="w-6 h-6 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" /></svg>',
    ],
    [
        'key' => 'points-payout',
        'label' => 'Points / Payout',
        'href' => route('race-management.points-payout'),
        'wire' => true,
        'icon' => '<svg class="w-6 h-6 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
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
$currentClass = $classes[0];

// Example completed races
$completedRaces = [
    ['id' => 1, 'name' => 'Stock Novice Q1', 'class' => 'Stock Novice', 'completed_at' => '10:32 AM'],
    ['id' => 2, 'name' => 'Stock Novice Q2', 'class' => 'Stock Novice', 'completed_at' => '11:15 AM'],
    ['id' => 3, 'name' => 'Stock Novice A Main', 'class' => 'Stock Novice', 'completed_at' => '2:45 PM'],
];

// Example results data
$results = [
    ['position' => 1, 'number' => '07', 'driver' => 'Sarah Williams', 'laps' => 28, 'time' => '8:02.156', 'best' => '16.234', 'points' => 100],
    ['position' => 2, 'number' => '01', 'driver' => 'Mike Johnson', 'laps' => 28, 'time' => '8:04.892', 'best' => '16.456', 'points' => 95],
    ['position' => 3, 'number' => '88', 'driver' => 'Jessica Lee', 'laps' => 28, 'time' => '8:06.234', 'best' => '16.567', 'points' => 91],
    ['position' => 4, 'number' => '15', 'driver' => 'Alex Turner', 'laps' => 27, 'time' => '8:01.456', 'best' => '16.789', 'points' => 87],
    ['position' => 5, 'number' => '33', 'driver' => 'Ryan Cooper', 'laps' => 27, 'time' => '8:03.123', 'best' => '16.901', 'points' => 84],
    ['position' => 6, 'number' => '42', 'driver' => 'Nicole Adams', 'laps' => 27, 'time' => '8:05.789', 'best' => '17.012', 'points' => 81],
    ['position' => 7, 'number' => '67', 'driver' => 'Brandon Hill', 'laps' => 26, 'time' => '8:01.456', 'best' => '17.234', 'points' => 78],
    ['position' => 8, 'number' => '99', 'driver' => 'Amanda Scott', 'laps' => 26, 'time' => '8:03.123', 'best' => '17.456', 'points' => 75],
];
?>

<x-layouts::module 
    title="Results"
    moduleTitle="RACE MANAGEMENT"
    :sidebarItems="$sidebarItems"
    currentItem="results"
    :showContextBar="true"
    :events="$events"
    :currentEvent="$currentEvent"
    :classes="$classes"
    :currentClass="$currentClass"
>
    <!-- List Panel - Completed Races -->
    <div class="w-full md:w-80 lg:w-96 flex flex-col bg-white dark:bg-zinc-800 border-r border-slate-200 dark:border-zinc-700 shrink-0">
        <!-- Header -->
        <div class="p-4 border-b border-slate-200 dark:border-zinc-700">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white">Completed Races</h2>
                <button class="inline-flex items-center gap-1.5 px-3 py-1.5 border border-slate-300 dark:border-zinc-600 text-slate-700 dark:text-slate-300 text-sm font-medium rounded-md hover:bg-slate-50 dark:hover:bg-zinc-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Export All
                </button>
            </div>
            <!-- Filter by Class -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 border border-slate-300 dark:border-zinc-600 rounded-md bg-white dark:bg-zinc-700 text-slate-900 dark:text-slate-100 text-sm">
                    <span>{{ $currentClass['name'] }}</span>
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Race List -->
        <div class="flex-1 overflow-y-auto">
            @foreach($completedRaces as $race)
                <a href="#" class="flex items-center gap-3 px-4 py-3 border-b border-slate-100 dark:border-zinc-700/50 hover:bg-slate-50 dark:hover:bg-zinc-700/50 transition-colors {{ $loop->last ? 'bg-red-50 dark:bg-red-900/20 border-l-4 border-l-red-500' : '' }}">
                    <!-- Trophy Icon -->
                    <div class="w-10 h-10 bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 rounded-lg flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                        </svg>
                    </div>
                    
                    <!-- Race Info -->
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-slate-900 dark:text-white truncate">{{ $race['name'] }}</p>
                        <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                            <span class="px-1.5 py-0.5 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded">Completed</span>
                            <span>{{ $race['completed_at'] }}</span>
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

    <!-- Results Panel -->
    <div class="flex-1 bg-slate-100 dark:bg-zinc-900 overflow-y-auto">
        <!-- Results Header -->
        <div class="bg-white dark:bg-zinc-800 border-b border-slate-200 dark:border-zinc-700 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Stock Novice A Main</h3>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="text-xs px-1.5 py-0.5 bg-sky-100 dark:bg-sky-900/30 text-sky-700 dark:text-sky-400 rounded">Stock Novice</span>
                        <span class="text-xs text-slate-500 dark:text-slate-400">8 laps • 8:02.156 best</span>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button class="inline-flex items-center gap-1.5 px-3 py-1.5 border border-slate-300 dark:border-zinc-600 text-slate-700 dark:text-slate-300 text-sm font-medium rounded-md hover:bg-slate-50 dark:hover:bg-zinc-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Print
                    </button>
                    <button class="inline-flex items-center gap-1.5 px-3 py-1.5 border border-slate-300 dark:border-zinc-600 text-slate-700 dark:text-slate-300 text-sm font-medium rounded-md hover:bg-slate-50 dark:hover:bg-zinc-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Export
                    </button>
                </div>
            </div>
        </div>

        <!-- Podium Display -->
        <div class="bg-gradient-to-b from-zinc-800 to-zinc-900 p-6">
            <div class="flex items-end justify-center gap-4 max-w-lg mx-auto">
                <!-- 2nd Place -->
                <div class="text-center">
                    <div class="w-16 h-16 mx-auto mb-2 bg-slate-300 rounded-full flex items-center justify-center text-slate-800 font-bold text-xl border-4 border-slate-200">
                        01
                    </div>
                    <p class="text-white font-medium text-sm">Mike Johnson</p>
                    <p class="text-slate-400 text-xs">28 Laps</p>
                    <div class="w-20 h-16 bg-slate-400 rounded-t-lg mt-2 flex items-center justify-center">
                        <span class="text-2xl font-bold text-slate-700">2</span>
                    </div>
                </div>
                <!-- 1st Place -->
                <div class="text-center">
                    <div class="w-20 h-20 mx-auto mb-2 bg-amber-400 rounded-full flex items-center justify-center text-amber-900 font-bold text-2xl border-4 border-amber-300 shadow-lg shadow-amber-500/30">
                        07
                    </div>
                    <p class="text-white font-semibold">Sarah Williams</p>
                    <p class="text-amber-400 text-xs font-medium">28 Laps • 8:02.156</p>
                    <div class="w-24 h-24 bg-amber-500 rounded-t-lg mt-2 flex items-center justify-center">
                        <span class="text-3xl font-bold text-amber-900">1</span>
                    </div>
                </div>
                <!-- 3rd Place -->
                <div class="text-center">
                    <div class="w-16 h-16 mx-auto mb-2 bg-amber-600 rounded-full flex items-center justify-center text-white font-bold text-xl border-4 border-amber-500">
                        88
                    </div>
                    <p class="text-white font-medium text-sm">Jessica Lee</p>
                    <p class="text-slate-400 text-xs">28 Laps</p>
                    <div class="w-20 h-12 bg-amber-700 rounded-t-lg mt-2 flex items-center justify-center">
                        <span class="text-2xl font-bold text-amber-200">3</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Full Results Table -->
        <div class="p-4">
            <div class="bg-white dark:bg-zinc-800 rounded-xl border border-slate-200 dark:border-zinc-700 overflow-hidden">
                <!-- Table Header -->
                <div class="grid grid-cols-12 gap-4 px-4 py-3 bg-slate-50 dark:bg-zinc-700/50 border-b border-slate-200 dark:border-zinc-700 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                    <div class="col-span-1">Pos</div>
                    <div class="col-span-1">No.</div>
                    <div class="col-span-3">Driver</div>
                    <div class="col-span-1 text-center">Laps</div>
                    <div class="col-span-2 text-right">Total Time</div>
                    <div class="col-span-2 text-right">Best Lap</div>
                    <div class="col-span-2 text-right">Points</div>
                </div>

                <!-- Results Rows -->
                @foreach($results as $result)
                    <div class="grid grid-cols-12 gap-4 px-4 py-3 border-b border-slate-100 dark:border-zinc-700/50 hover:bg-slate-50 dark:hover:bg-zinc-700/30 transition-colors items-center {{ $loop->first ? 'bg-amber-50 dark:bg-amber-900/10' : '' }}">
                        <div class="col-span-1">
                            <span class="w-8 h-8 flex items-center justify-center font-bold text-lg rounded
                                @if($result['position'] === 1) bg-amber-400 text-amber-900
                                @elseif($result['position'] === 2) bg-slate-300 text-slate-800
                                @elseif($result['position'] === 3) bg-amber-600 text-white
                                @else bg-zinc-200 dark:bg-zinc-700 text-zinc-700 dark:text-zinc-300 @endif">
                                {{ $result['position'] }}
                            </span>
                        </div>
                        <div class="col-span-1">
                            <span class="px-2 py-1 bg-red-600 text-white font-bold text-sm rounded">
                                {{ $result['number'] }}
                            </span>
                        </div>
                        <div class="col-span-3 font-medium text-slate-900 dark:text-white">
                            {{ $result['driver'] }}
                        </div>
                        <div class="col-span-1 text-center font-mono text-lg font-semibold text-slate-900 dark:text-white">
                            {{ $result['laps'] }}
                        </div>
                        <div class="col-span-2 text-right font-mono text-slate-600 dark:text-slate-400">
                            {{ $result['time'] }}
                        </div>
                        <div class="col-span-2 text-right font-mono {{ $loop->first ? 'text-green-600 dark:text-green-400 font-semibold' : 'text-slate-600 dark:text-slate-400' }}">
                            {{ $result['best'] }}
                        </div>
                        <div class="col-span-2 text-right font-semibold {{ $loop->first ? 'text-amber-600 dark:text-amber-400' : 'text-slate-700 dark:text-slate-300' }}">
                            {{ $result['points'] }} pts
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-layouts::module>
