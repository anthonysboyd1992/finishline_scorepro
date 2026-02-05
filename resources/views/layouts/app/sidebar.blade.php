@props(['title' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    @include('partials.head')
</head>
<body class="min-h-screen bg-slate-100 dark:bg-zinc-900">
    <!-- Top Navigation -->
    <header class="sticky top-0 z-50 bg-zinc-800 shadow-lg">
        <div class="flex h-14 items-center justify-between px-4 md:px-6">
            <!-- Logo & App Name -->
            <div class="flex items-center gap-4">
                <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-3">
                    <x-app-logo-icon class="h-8 w-8 text-white" />
                    <span class="text-lg font-semibold tracking-wide text-white hidden sm:inline">{{ config('app.name', 'FinishLine Pro') }}</span>
                </a>

                <div class="h-6 w-px bg-white/20 hidden sm:block"></div>

                @include('partials.sanctions-dropdown')
            </div>

            <!-- User Menu -->
            <div class="flex items-center gap-3">
                <!-- Settings Link -->
                <a href="{{ route('profile.edit') }}" wire:navigate class="hidden sm:flex items-center gap-2 px-3 py-2 text-sm text-white/80 hover:text-white hover:bg-white/10 rounded-md transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </a>

                <!-- User Dropdown -->
                <div 
                    x-data="{ open: false }" 
                    @keydown.escape.window="open = false"
                    class="relative"
                >
                    <button 
                        @click="open = !open" 
                        class="flex items-center gap-2 rounded-md px-2 py-1.5 hover:bg-white/10 transition-colors"
                    >
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-red-500 text-sm font-medium text-white">
                            {{ auth()->user()->initials() }}
                        </div>
                        <span class="hidden sm:inline text-sm text-white">{{ auth()->user()->name }}</span>
                        <svg class="w-4 h-4 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Dropdown Menu -->
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
                        class="absolute right-0 mt-2 w-56 origin-top-right rounded-lg bg-white dark:bg-zinc-800 shadow-lg ring-1 ring-black/5 dark:ring-white/10 z-50"
                    >
                        <div class="p-2">
                            <!-- User Info -->
                            <div class="px-3 py-2 border-b border-slate-200 dark:border-zinc-700 mb-2">
                                <p class="text-sm font-medium text-slate-900 dark:text-white">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ auth()->user()->email }}</p>
                            </div>


                            <!-- Settings -->
                            <a href="{{ route('profile.edit') }}" wire:navigate class="flex items-center gap-2 px-3 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-zinc-700 rounded-md">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                {{ __('Settings') }}
                            </a>

                            <!-- Logout -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex w-full items-center gap-2 px-3 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-md" data-test="logout-button">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    {{ __('Log Out') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        {{ $slot }}
    </main>

    @livewireScripts
</body>
</html>
