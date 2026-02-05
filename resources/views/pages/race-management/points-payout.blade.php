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
$currentClass = null;

// Example standings data
$standings = [
    ['position' => 1, 'number' => '07', 'driver' => 'Sarah Williams', 'points' => 286, 'races' => 3, 'wins' => 2, 'payout' => 150.00],
    ['position' => 2, 'number' => '01', 'driver' => 'Mike Johnson', 'points' => 272, 'races' => 3, 'wins' => 1, 'payout' => 100.00],
    ['position' => 3, 'number' => '88', 'driver' => 'Jessica Lee', 'points' => 258, 'races' => 3, 'wins' => 0, 'payout' => 75.00],
    ['position' => 4, 'number' => '15', 'driver' => 'Alex Turner', 'points' => 245, 'races' => 3, 'wins' => 0, 'payout' => 50.00],
    ['position' => 5, 'number' => '33', 'driver' => 'Ryan Cooper', 'points' => 234, 'races' => 3, 'wins' => 0, 'payout' => 25.00],
    ['position' => 6, 'number' => '42', 'driver' => 'Nicole Adams', 'points' => 221, 'races' => 3, 'wins' => 0, 'payout' => 0.00],
    ['position' => 7, 'number' => '67', 'driver' => 'Brandon Hill', 'points' => 208, 'races' => 3, 'wins' => 0, 'payout' => 0.00],
    ['position' => 8, 'number' => '99', 'driver' => 'Amanda Scott', 'points' => 195, 'races' => 3, 'wins' => 0, 'payout' => 0.00],
];

// Summary stats
$totalPayout = array_sum(array_column($standings, 'payout'));
$totalEntries = count($standings);
?>

<x-layouts::module 
    title="Points / Payout"
    moduleTitle="RACE MANAGEMENT"
    :sidebarItems="$sidebarItems"
    currentItem="points-payout"
    :showContextBar="true"
    :events="$events"
    :currentEvent="$currentEvent"
    :classes="$classes"
    :currentClass="$currentClass"
>
    <!-- Full Width Content -->
    <div class="flex-1 bg-slate-100 dark:bg-zinc-900 overflow-y-auto">
        <!-- Summary Cards -->
        <div class="p-4 bg-white dark:bg-zinc-800 border-b border-slate-200 dark:border-zinc-700">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Total Entries -->
                <div class="bg-slate-50 dark:bg-zinc-700/50 rounded-lg p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-sky-100 dark:bg-sky-900/30 text-sky-600 dark:text-sky-400 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-slate-500 dark:text-slate-400">Total Entries</p>
                            <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $totalEntries }}</p>
                        </div>
                    </div>
                </div>

                <!-- Total Payout -->
                <div class="bg-slate-50 dark:bg-zinc-700/50 rounded-lg p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-slate-500 dark:text-slate-400">Total Payout</p>
                            <p class="text-2xl font-bold text-green-600 dark:text-green-400">${{ number_format($totalPayout, 2) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Races Completed -->
                <div class="bg-slate-50 dark:bg-zinc-700/50 rounded-lg p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M4 4v16h2V4H4zm4 0v4h2v4h2V8h2v4h2V8h2v4h2V4H8z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-slate-500 dark:text-slate-400">Races Completed</p>
                            <p class="text-2xl font-bold text-slate-900 dark:text-white">3</p>
                        </div>
                    </div>
                </div>

                <!-- Points Leader -->
                <div class="bg-slate-50 dark:bg-zinc-700/50 rounded-lg p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-slate-500 dark:text-slate-400">Points Leader</p>
                            <p class="text-lg font-bold text-slate-900 dark:text-white">Sarah Williams</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Class Selection Tabs -->
        <div class="bg-white dark:bg-zinc-800 border-b border-slate-200 dark:border-zinc-700 px-4">
            <nav class="flex gap-1 overflow-x-auto py-2">
                @foreach($classes as $class)
                    <button class="px-4 py-2 text-sm font-medium rounded-lg whitespace-nowrap transition-colors {{ $loop->first ? 'bg-red-600 text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-zinc-700' }}">
                        {{ $class['name'] }}
                    </button>
                @endforeach
                <button class="px-4 py-2 text-sm font-medium text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-zinc-700 rounded-lg whitespace-nowrap">
                    All Classes
                </button>
            </nav>
        </div>

        <!-- Standings Table -->
        <div class="p-4">
            <div class="bg-white dark:bg-zinc-800 rounded-xl border border-slate-200 dark:border-zinc-700 overflow-hidden">
                <!-- Header -->
                <div class="flex items-center justify-between p-4 border-b border-slate-200 dark:border-zinc-700">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Stock Novice Standings</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400">After 3 races</p>
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

                <!-- Table Header -->
                <div class="grid grid-cols-12 gap-4 px-4 py-3 bg-slate-50 dark:bg-zinc-700/50 border-b border-slate-200 dark:border-zinc-700 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                    <div class="col-span-1">Pos</div>
                    <div class="col-span-1">No.</div>
                    <div class="col-span-3">Driver</div>
                    <div class="col-span-2 text-center">Points</div>
                    <div class="col-span-1 text-center">Races</div>
                    <div class="col-span-1 text-center">Wins</div>
                    <div class="col-span-3 text-right">Payout</div>
                </div>

                <!-- Standings Rows -->
                @foreach($standings as $standing)
                    <div class="grid grid-cols-12 gap-4 px-4 py-3 border-b border-slate-100 dark:border-zinc-700/50 hover:bg-slate-50 dark:hover:bg-zinc-700/30 transition-colors items-center {{ $loop->first ? 'bg-amber-50 dark:bg-amber-900/10' : '' }}">
                        <div class="col-span-1">
                            <span class="w-8 h-8 flex items-center justify-center font-bold text-lg rounded
                                @if($standing['position'] === 1) bg-amber-400 text-amber-900
                                @elseif($standing['position'] === 2) bg-slate-300 text-slate-800
                                @elseif($standing['position'] === 3) bg-amber-600 text-white
                                @else bg-zinc-200 dark:bg-zinc-700 text-zinc-700 dark:text-zinc-300 @endif">
                                {{ $standing['position'] }}
                            </span>
                        </div>
                        <div class="col-span-1">
                            <span class="px-2 py-1 bg-red-600 text-white font-bold text-sm rounded">
                                {{ $standing['number'] }}
                            </span>
                        </div>
                        <div class="col-span-3 font-medium text-slate-900 dark:text-white">
                            {{ $standing['driver'] }}
                        </div>
                        <div class="col-span-2 text-center">
                            <span class="text-xl font-bold {{ $loop->first ? 'text-amber-600 dark:text-amber-400' : 'text-slate-900 dark:text-white' }}">
                                {{ $standing['points'] }}
                            </span>
                        </div>
                        <div class="col-span-1 text-center text-slate-600 dark:text-slate-400">
                            {{ $standing['races'] }}
                        </div>
                        <div class="col-span-1 text-center">
                            @if($standing['wins'] > 0)
                                <span class="inline-flex items-center gap-1 text-amber-600 dark:text-amber-400 font-semibold">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                    {{ $standing['wins'] }}
                                </span>
                            @else
                                <span class="text-slate-400 dark:text-slate-500">-</span>
                            @endif
                        </div>
                        <div class="col-span-3 text-right">
                            @if($standing['payout'] > 0)
                                <span class="text-lg font-bold text-green-600 dark:text-green-400">
                                    ${{ number_format($standing['payout'], 2) }}
                                </span>
                            @else
                                <span class="text-slate-400 dark:text-slate-500">-</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-layouts::module>
