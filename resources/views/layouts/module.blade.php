@props([
    'title' => '',
    'moduleTitle' => '',
    'backRoute' => 'dashboard',
    'sidebarItems' => [],
    'currentItem' => null,
    'sanctions' => [],
    'currentSanction' => null,
    'showContextBar' => false,
    'events' => [],
    'currentEvent' => null,
    'classes' => [],
    'currentClass' => null,
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    @include('partials.head')
</head>
<body class="h-screen overflow-hidden bg-slate-100 dark:bg-zinc-900">
    <div class="flex h-screen flex-col">
        <!-- Top Header Bar -->
        <header class="flex h-12 items-center justify-between bg-zinc-800 px-4 shrink-0">
            <div class="flex items-center gap-4">
                <!-- Back Button -->
                <a href="{{ route($backRoute) }}" wire:navigate class="flex items-center gap-2 text-white/80 hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span class="text-sm font-medium hidden sm:inline">{{ __('Back') }}</span>
                </a>
                
                <div class="h-6 w-px bg-white/20 hidden sm:block"></div>
                
                <span class="text-lg font-semibold tracking-wide text-white hidden sm:inline">{{ config('app.name', 'FinishLine Pro') }}</span>

                <div class="h-6 w-px bg-white/20 hidden sm:block"></div>

                @include('partials.sanctions-dropdown', ['sanctions' => $sanctions, 'currentSanction' => $currentSanction])
            </div>
            
            <div class="flex items-center gap-3">
                <!-- User Menu -->
                <div 
                    x-data="{ open: false }" 
                    @keydown.escape.window="open = false"
                    class="relative"
                >
                    <button 
                        @click="open = !open" 
                        class="flex items-center gap-2 rounded-md px-2 py-1.5 hover:bg-white/10 transition-colors"
                    >
                        <div class="flex h-7 w-7 items-center justify-center rounded-full bg-red-500 text-xs font-medium text-white">
                            {{ auth()->user()->initials() }}
                        </div>
                        <svg class="w-4 h-4 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Dropdown Menu -->
                    <div 
                        x-show="open" 
                        @click.away="open = false"
                        x-transition
                        x-cloak
                        class="absolute right-0 mt-2 w-48 origin-top-right rounded-lg bg-white dark:bg-zinc-800 shadow-lg ring-1 ring-black/5 dark:ring-white/10 z-50"
                    >
                        <div class="p-2">
                            <div class="px-3 py-2 border-b border-slate-200 dark:border-zinc-700 mb-2">
                                <p class="text-sm font-medium text-slate-900 dark:text-white truncate">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ auth()->user()->email }}</p>
                            </div>
                            
                            <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-2 px-3 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-zinc-700 rounded-md">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                                {{ __('Dashboard') }}
                            </a>
                            
                            <a href="{{ route('profile.edit') }}" wire:navigate class="flex items-center gap-2 px-3 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-zinc-700 rounded-md">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                {{ __('Settings') }}
                            </a>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex w-full items-center gap-2 px-3 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-md">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                                    {{ __('Log Out') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        @if($showContextBar)
        <!-- Context Selection Bar (Event & Class) -->
        <div class="bg-slate-100 dark:bg-zinc-800 border-b border-slate-200 dark:border-zinc-700 px-4 py-3 shrink-0">
            <div class="flex flex-col sm:flex-row gap-3 sm:gap-6">
                <!-- Event Selector -->
                <div 
                    x-data="{ open: false }" 
                    @keydown.escape.window="open = false"
                    class="relative flex-1 sm:max-w-sm"
                >
                    <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">
                        Event
                    </label>
                    <button 
                        @click="open = !open" 
                        class="w-full flex items-center gap-3 rounded-xl px-4 py-3 bg-white dark:bg-zinc-700 border-2 border-emerald-500 hover:border-emerald-600 shadow-sm transition-all"
                    >
                        <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0 text-left">
                            <p class="text-base font-semibold text-slate-900 dark:text-white truncate">{{ $currentEvent['name'] ?? 'Select Event' }}</p>
                            @if(isset($currentEvent['date']))
                                <p class="text-sm text-slate-500 dark:text-slate-400">{{ $currentEvent['date'] }}</p>
                            @endif
                        </div>
                        <svg class="w-5 h-5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div 
                        x-show="open" 
                        @click.away="open = false"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        x-cloak
                        class="absolute left-0 right-0 mt-2 origin-top rounded-xl bg-white dark:bg-zinc-800 shadow-xl ring-1 ring-black/5 dark:ring-white/10 z-50 max-h-80 overflow-y-auto"
                    >
                        <div class="p-2">
                            @forelse($events as $event)
                                <a 
                                    href="{{ $event['href'] ?? '#' }}"
                                    @if($event['wire'] ?? false) wire:navigate @endif
                                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ ($currentEvent['id'] ?? null) === ($event['id'] ?? null) ? 'bg-emerald-50 dark:bg-emerald-900/20' : 'hover:bg-slate-50 dark:hover:bg-zinc-700' }}"
                                >
                                    <div class="w-10 h-10 rounded-lg flex items-center justify-center shrink-0 {{ ($currentEvent['id'] ?? null) === ($event['id'] ?? null) ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400' : 'bg-slate-100 dark:bg-zinc-700 text-slate-500 dark:text-slate-400' }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-semibold text-slate-900 dark:text-white truncate {{ ($currentEvent['id'] ?? null) === ($event['id'] ?? null) ? 'text-emerald-700 dark:text-emerald-400' : '' }}">{{ $event['name'] ?? '' }}</p>
                                        @if(isset($event['date']))
                                            <p class="text-sm text-slate-500 dark:text-slate-400">{{ $event['date'] }}</p>
                                        @endif
                                    </div>
                                    @if(($currentEvent['id'] ?? null) === ($event['id'] ?? null))
                                        <svg class="w-6 h-6 text-emerald-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    @endif
                                </a>
                            @empty
                                <p class="px-4 py-3 text-sm text-slate-500 dark:text-slate-400">No events available</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Class Selector -->
                <div 
                    x-data="{ open: false }" 
                    @keydown.escape.window="open = false"
                    class="relative flex-1 sm:max-w-xs"
                >
                    <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">
                        Class
                    </label>
                    <button 
                        @click="open = !open" 
                        class="w-full flex items-center gap-3 rounded-xl px-4 py-3 bg-white dark:bg-zinc-700 border-2 border-sky-500 hover:border-sky-600 shadow-sm transition-all"
                    >
                        <div class="w-10 h-10 bg-sky-100 dark:bg-sky-900/30 text-sky-600 dark:text-sky-400 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0 text-left">
                            <p class="text-base font-semibold text-slate-900 dark:text-white truncate">{{ $currentClass['name'] ?? 'All Classes' }}</p>
                            <p class="text-sm text-slate-500 dark:text-slate-400">{{ is_null($currentClass) ? 'Showing all' : 'Filtered' }}</p>
                        </div>
                        <svg class="w-5 h-5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div 
                        x-show="open" 
                        @click.away="open = false"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        x-cloak
                        class="absolute left-0 right-0 mt-2 origin-top rounded-xl bg-white dark:bg-zinc-800 shadow-xl ring-1 ring-black/5 dark:ring-white/10 z-50 max-h-80 overflow-y-auto"
                    >
                        <div class="p-2">
                            <!-- All Classes option -->
                            <a 
                                href="#"
                                class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ is_null($currentClass) ? 'bg-sky-50 dark:bg-sky-900/20' : 'hover:bg-slate-50 dark:hover:bg-zinc-700' }}"
                            >
                                <div class="w-10 h-10 rounded-lg flex items-center justify-center shrink-0 {{ is_null($currentClass) ? 'bg-sky-100 dark:bg-sky-900/30 text-sky-600 dark:text-sky-400' : 'bg-slate-100 dark:bg-zinc-700 text-slate-500 dark:text-slate-400' }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-slate-900 dark:text-white {{ is_null($currentClass) ? 'text-sky-700 dark:text-sky-400' : '' }}">All Classes</p>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">Show all entries</p>
                                </div>
                                @if(is_null($currentClass))
                                    <svg class="w-6 h-6 text-sky-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                @endif
                            </a>
                            @foreach($classes as $class)
                                <a 
                                    href="{{ $class['href'] ?? '#' }}"
                                    @if($class['wire'] ?? false) wire:navigate @endif
                                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors {{ ($currentClass['id'] ?? null) === ($class['id'] ?? null) ? 'bg-sky-50 dark:bg-sky-900/20' : 'hover:bg-slate-50 dark:hover:bg-zinc-700' }}"
                                >
                                    <div class="w-10 h-10 rounded-lg flex items-center justify-center shrink-0 {{ ($currentClass['id'] ?? null) === ($class['id'] ?? null) ? 'bg-sky-100 dark:bg-sky-900/30 text-sky-600 dark:text-sky-400' : 'bg-slate-100 dark:bg-zinc-700 text-slate-500 dark:text-slate-400' }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-semibold text-slate-900 dark:text-white truncate {{ ($currentClass['id'] ?? null) === ($class['id'] ?? null) ? 'text-sky-700 dark:text-sky-400' : '' }}">{{ $class['name'] ?? '' }}</p>
                                    </div>
                                    @if(($currentClass['id'] ?? null) === ($class['id'] ?? null))
                                        <svg class="w-6 h-6 text-sky-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Entry count badge -->
                @if(isset($entryCount))
                <div class="flex items-end pb-2 sm:pb-0 sm:items-center">
                    <div class="flex items-center gap-2 px-4 py-2 bg-white dark:bg-zinc-700 rounded-xl border border-slate-200 dark:border-zinc-600">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span class="text-sm text-slate-500 dark:text-slate-400">Entries:</span>
                        <span class="text-lg font-bold text-slate-900 dark:text-white">{{ $entryCount }}</span>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Main Content Area -->
        <div class="flex flex-1 overflow-hidden">
            <!-- Red Sidebar Navigation -->
            <nav class="flex w-20 md:w-28 flex-col bg-[#c73e3e] text-white shrink-0">
                <div class="flex-1 overflow-y-auto py-2">
                    @foreach($sidebarItems as $item)
                        <a 
                            href="{{ $item['href'] ?? '#' }}"
                            @if($item['wire'] ?? false) wire:navigate @endif
                            class="flex flex-col items-center justify-center px-2 py-3 md:py-4 text-center transition-colors hover:bg-[#b53636] {{ ($currentItem === ($item['key'] ?? '')) ? 'bg-[#a52f2f]' : '' }}"
                        >
                            <div class="w-8 h-8 md:w-10 md:h-10 flex items-center justify-center">
                                {!! $item['icon'] ?? '' !!}
                            </div>
                            <span class="mt-1 text-[10px] md:text-xs font-medium leading-tight">{{ $item['label'] ?? '' }}</span>
                        </a>
                    @endforeach
                </div>
            </nav>

            <!-- Content Panels -->
            <div class="flex flex-1 overflow-hidden">
                {{ $slot }}
            </div>
        </div>
    </div>

    @livewireScripts
</body>
</html>
