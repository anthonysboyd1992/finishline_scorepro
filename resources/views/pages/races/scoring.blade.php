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

// Example scoring data
$currentRace = ['name' => 'Stock Novice Q1', 'class' => 'Stock Novice', 'status' => 'running', 'lap' => 12, 'time' => '3:45'];
$results = [
    ['position' => 1, 'number' => '07', 'driver' => 'Sarah Williams', 'laps' => 12, 'time' => '3:42.156', 'best' => '18.234', 'gap' => '-'],
    ['position' => 2, 'number' => '01', 'driver' => 'Mike Johnson', 'laps' => 12, 'time' => '3:44.892', 'best' => '18.456', 'gap' => '+2.736'],
    ['position' => 3, 'number' => '88', 'driver' => 'Jessica Lee', 'laps' => 12, 'time' => '3:46.234', 'best' => '18.567', 'gap' => '+4.078'],
    ['position' => 4, 'number' => '15', 'driver' => 'Alex Turner', 'laps' => 11, 'time' => '3:41.456', 'best' => '18.789', 'gap' => '-1 Lap'],
    ['position' => 5, 'number' => '33', 'driver' => 'Ryan Cooper', 'laps' => 11, 'time' => '3:43.123', 'best' => '18.901', 'gap' => '-1 Lap'],
    ['position' => 6, 'number' => '42', 'driver' => 'Nicole Adams', 'laps' => 11, 'time' => '3:45.789', 'best' => '19.012', 'gap' => '-1 Lap'],
    ['position' => 7, 'number' => '67', 'driver' => 'Brandon Hill', 'laps' => 10, 'time' => '3:38.456', 'best' => '19.234', 'gap' => '-2 Laps'],
    ['position' => 8, 'number' => '99', 'driver' => 'Amanda Scott', 'laps' => 10, 'time' => '3:40.123', 'best' => '19.456', 'gap' => '-2 Laps'],
];
?>

<x-layouts::module 
    title="Scoring"
    moduleTitle="RACES"
    :sidebarItems="$sidebarItems"
    currentItem="scoring"
    :showContextBar="true"
    :events="$events"
    :currentEvent="$currentEvent"
    :classes="$classes"
    :currentClass="$currentClass"
>
    <!-- Full Width Scoring Display -->
    <div class="flex-1 bg-slate-100 dark:bg-zinc-900 overflow-y-auto">
        <!-- Race Header -->
        <div class="bg-zinc-800 dark:bg-zinc-950 text-white p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div>
                        <h3 class="text-xl font-bold">{{ $currentRace['name'] }}</h3>
                        <span class="text-xs px-2 py-0.5 bg-sky-500/20 text-sky-300 rounded">{{ $currentRace['class'] }}</span>
                    </div>
                </div>
                <div class="flex items-center gap-6">
                    <!-- Race Time -->
                    <div class="text-center">
                        <p class="text-xs text-white/60 uppercase tracking-wider">Time</p>
                        <p class="text-3xl font-mono font-bold text-amber-400">{{ $currentRace['time'] }}</p>
                    </div>
                    <!-- Lap Counter -->
                    <div class="text-center">
                        <p class="text-xs text-white/60 uppercase tracking-wider">Lap</p>
                        <p class="text-3xl font-mono font-bold">{{ $currentRace['lap'] }}</p>
                    </div>
                    <!-- Race Status -->
                    <div class="flex items-center gap-2">
                        @if($currentRace['status'] === 'running')
                            <span class="relative flex h-3 w-3">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                            </span>
                            <span class="text-green-400 font-semibold uppercase">Racing</span>
                        @else
                            <span class="text-slate-400 font-semibold uppercase">Finished</span>
                        @endif
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-black font-semibold rounded-md transition-colors">
                        Pause
                    </button>
                    <button class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-md transition-colors">
                        Stop Race
                    </button>
                </div>
            </div>
        </div>

        <!-- Results Table -->
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
                    <div class="col-span-2 text-right">Gap</div>
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
                        <div class="col-span-2 text-right font-mono {{ str_contains($result['gap'], 'Lap') ? 'text-red-600 dark:text-red-400' : 'text-slate-600 dark:text-slate-400' }}">
                            {{ $result['gap'] }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-layouts::module>
