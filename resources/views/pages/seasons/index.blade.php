@php
$sidebarItems = [
    [
        'key' => 'seasons',
        'label' => 'Seasons',
        'href' => route('seasons.index'),
        'wire' => true,
        'icon' => '<svg class="w-6 h-6 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>',
    ],
    [
        'key' => 'events',
        'label' => 'Events',
        'href' => '#',
        'icon' => '<svg class="w-6 h-6 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>',
    ],
    [
        'key' => 'standings',
        'label' => 'Standings',
        'href' => '#',
        'icon' => '<svg class="w-6 h-6 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>',
    ],
    [
        'key' => 'points',
        'label' => 'Points',
        'href' => '#',
        'icon' => '<svg class="w-6 h-6 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" /></svg>',
    ],
];
@endphp

<x-layouts::module 
    title="Season Management"
    moduleTitle="SEASONS"
    :sidebarItems="$sidebarItems"
    currentItem="seasons"
>
    <!-- List Panel -->
    <x-module.list-panel 
        title="Search for a Season"
        searchPlaceholder="Search seasons..."
        newButtonText="New Season"
        newButtonHref="#"
    >
        <!-- Season list will go here -->
        <div class="py-8 text-center text-slate-500 dark:text-slate-400">
            <p>No seasons found</p>
            <p class="text-sm mt-1">Create your first season to get started</p>
        </div>
    </x-module.list-panel>

    <!-- Detail Panel -->
    <x-module.detail-panel 
        emptyMessage="Please select a season"
        emptyLinkText="new season"
        emptyLinkHref="#"
    />
</x-layouts::module>
