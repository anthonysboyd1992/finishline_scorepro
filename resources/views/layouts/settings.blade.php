@props([
    'title' => 'Settings',
    'heading' => '',
    'subheading' => '',
])

@php
$sidebarItems = [
    [
        'key' => 'profile',
        'label' => 'Profile',
        'href' => route('profile.edit'),
        'wire' => true,
        'icon' => '<svg class="w-6 h-6 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>',
    ],
    [
        'key' => 'password',
        'label' => 'Password',
        'href' => route('user-password.edit'),
        'wire' => true,
        'icon' => '<svg class="w-6 h-6 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>',
    ],
];

if (Laravel\Fortify\Features::canManageTwoFactorAuthentication()) {
    $sidebarItems[] = [
        'key' => 'two-factor',
        'label' => '2FA',
        'href' => route('two-factor.show'),
        'wire' => true,
        'icon' => '<svg class="w-6 h-6 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>',
    ];
}

$sidebarItems[] = [
    'key' => 'appearance',
    'label' => 'Appearance',
    'href' => route('appearance.edit'),
    'wire' => true,
    'icon' => '<svg class="w-6 h-6 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" /></svg>',
];

$currentItem = match(true) {
    request()->routeIs('profile.edit') => 'profile',
    request()->routeIs('user-password.edit') => 'password',
    request()->routeIs('two-factor.show') => 'two-factor',
    request()->routeIs('appearance.edit') => 'appearance',
    default => 'profile',
};
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head')
</head>
<body class="h-dvh overflow-hidden bg-slate-100 dark:bg-zinc-900">
    <div class="flex h-dvh flex-col">
        <!-- Top Header Bar -->
        <header class="flex h-12 items-center justify-between bg-zinc-800 px-4 shrink-0">
            <div class="flex items-center gap-4">
                <!-- Back Button -->
                <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-2 text-white/80 hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span class="text-sm font-medium hidden sm:inline">{{ __('Back') }}</span>
                </a>
                
                <div class="h-6 w-px bg-white/20 hidden sm:block"></div>
                
                <span class="text-lg font-semibold tracking-wide text-white hidden sm:inline">{{ config('app.name', 'FinishLine Pro') }}</span>

                <div class="h-6 w-px bg-white/20 hidden sm:block"></div>

                @include('partials.sanctions-dropdown')
            </div>
            
            <div class="flex items-center gap-3">
                @include('partials.dark-mode-toggle')

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

            <!-- Content Panel -->
            <div class="flex-1 bg-slate-100 dark:bg-zinc-900 overflow-y-auto p-6 md:p-8">
                <div class="max-w-2xl">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>

    @livewireScripts
</body>
</html>
