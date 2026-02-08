@props([
    'title' => '',
    'searchPlaceholder' => 'Search...',
    'newButtonText' => 'New Item',
    'newButtonHref' => '#',
])

<div class="flex w-full max-w-md flex-col border-r border-slate-300 dark:border-zinc-700 bg-white dark:bg-zinc-800">
    <!-- Header with Search -->
    <div class="bg-slate-500 dark:bg-zinc-700 p-4">
        <h2 class="mb-3 text-center text-sm font-bold tracking-widest text-white uppercase">{{ $title }}</h2>
        
        <div class="relative">
            <input 
                type="text"
                placeholder="{{ $searchPlaceholder }}"
                {{ $attributes->whereStartsWith('wire:') }}
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
        {{ $slot }}
    </div>

    <!-- New Button -->
    <div class="p-3 border-t border-slate-200 dark:border-zinc-700">
        <a 
            href="{{ $newButtonHref }}"
            class="flex w-full items-center justify-center gap-2 rounded-md bg-blue-600 px-4 py-3 text-sm font-medium text-white hover:bg-blue-700 transition-colors"
        >
            {{ $newButtonText }}
        </a>
    </div>
</div>
