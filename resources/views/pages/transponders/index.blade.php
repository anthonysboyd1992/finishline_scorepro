@php
$sidebarItems = [
    [
        'key' => 'all',
        'label' => 'All',
        'href' => route('transponders.index'),
        'wire' => true,
        'icon' => '<svg class="w-6 h-6 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" /></svg>',
    ],
];
@endphp

<x-layouts::module 
    title="Club Transponders"
    moduleTitle="TRANSPONDERS"
    :sidebarItems="$sidebarItems"
    currentItem="all"
>
    <!-- List Panel -->
    <div class="flex w-full max-w-md flex-col border-r border-slate-300 dark:border-zinc-700 bg-white dark:bg-zinc-800">
        <!-- Header with Search -->
        <div class="bg-slate-500 dark:bg-zinc-700 p-4">
            <h2 class="mb-3 text-center text-sm font-bold tracking-widest text-white uppercase">{{ __('Club Transponders') }}</h2>
            
            <div class="relative">
                <input 
                    type="text"
                    placeholder="{{ __('Search alias or number...') }}"
                    class="w-full rounded-md border-2 border-slate-600 dark:border-zinc-600 bg-white dark:bg-zinc-800 px-3 py-2 pr-8 text-slate-900 dark:text-white placeholder-slate-400 focus:border-blue-500 focus:outline-hidden focus:ring-2 focus:ring-blue-500"
                />
                <button type="button" class="absolute right-2 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- List Content -->
        <div class="flex-1 overflow-y-auto">
            <!-- Example transponder items -->
            <div class="divide-y divide-slate-200 dark:divide-zinc-700">
                <a href="#" class="block px-4 py-3 hover:bg-slate-50 dark:hover:bg-zinc-700 transition-colors">
                    <span class="text-lg font-bold text-red-600 dark:text-red-400">A01</span>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 font-mono">1234567890123</p>
                </a>
                
                <a href="#" class="block px-4 py-3 hover:bg-slate-50 dark:hover:bg-zinc-700 transition-colors">
                    <span class="text-lg font-bold text-red-600 dark:text-red-400">A02</span>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 font-mono">9876543210987</p>
                </a>
                
                <a href="#" class="block px-4 py-3 hover:bg-slate-50 dark:hover:bg-zinc-700 transition-colors">
                    <span class="text-lg font-bold text-red-600 dark:text-red-400">B01</span>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 font-mono">5555666677778</p>
                </a>
            </div>
            
            <div class="py-8 text-center text-slate-500 dark:text-slate-400 border-t border-slate-200 dark:border-zinc-700">
                <p class="text-sm">{{ __('Type to search') }}</p>
                <p class="text-xs mt-1">{{ __('Use short alias (A01) or full number') }}</p>
            </div>
        </div>

        <!-- New Button -->
        <div class="p-3 border-t border-slate-200 dark:border-zinc-700">
            <button 
                type="button"
                class="flex w-full items-center justify-center gap-2 rounded-md bg-[#c73e3e] px-4 py-3 text-sm font-medium text-white hover:bg-[#b53636] transition-colors"
            >
                {{ __('Add Transponder') }}
            </button>
        </div>
    </div>

    <!-- Detail Panel -->
    <div class="flex-1 bg-slate-100 dark:bg-zinc-900 overflow-y-auto">
        <!-- Empty State -->
        <div class="flex h-full items-center justify-center">
            <div class="text-center max-w-sm px-4">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-slate-200 dark:bg-zinc-800 flex items-center justify-center">
                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-slate-900 dark:text-white mb-2">{{ __('Select a Transponder') }}</h3>
                <p class="text-slate-600 dark:text-slate-400 text-sm">
                    {{ __('Search by alias or number, then select to view details.') }}
                </p>
            </div>
        </div>
    </div>
</x-layouts::module>
