<?php

use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::settings')] class extends Component {
    public string $appearance = 'system';

    public function mount(): void
    {
        $this->appearance = auth()->user()->appearance ?? 'system';
    }

    public function updateAppearance(string $value): void
    {
        $this->appearance = $value;

        auth()->user()->update(['appearance' => $value]);
    }
}; ?>

<div class="space-y-6">
    <!-- Page Header -->
    <div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ __('Appearance') }}</h1>
        <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">{{ __('Update the appearance settings for your account') }}</p>
    </div>

    <!-- Appearance Options -->
    <div class="bg-white dark:bg-zinc-800 rounded-xl border border-slate-200 dark:border-zinc-700 p-6">
        <div
            x-data="{
                appearance: @entangle('appearance'),
                applyTheme(value) {
                    if (value === 'system') {
                        localStorage.setItem('theme', 'system');
                        if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                            document.documentElement.classList.add('dark');
                        } else {
                            document.documentElement.classList.remove('dark');
                        }
                    } else if (value === 'dark') {
                        localStorage.setItem('theme', 'dark');
                        document.documentElement.classList.add('dark');
                    } else {
                        localStorage.setItem('theme', 'light');
                        document.documentElement.classList.remove('dark');
                    }
                }
            }"
            x-effect="applyTheme(appearance)"
            class="flex flex-wrap gap-3"
        >
            <!-- Light Mode -->
            <button
                type="button"
                @click="$wire.updateAppearance('light')"
                :class="appearance === 'light' ? 'ring-2 ring-red-500 border-red-500' : 'border-slate-300 dark:border-zinc-600'"
                class="flex items-center gap-3 px-4 py-3 bg-white dark:bg-zinc-700 border rounded-lg transition-all hover:border-red-300 dark:hover:border-red-600"
            >
                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <span class="font-medium text-slate-900 dark:text-white">{{ __('Light') }}</span>
            </button>

            <!-- Dark Mode -->
            <button
                type="button"
                @click="$wire.updateAppearance('dark')"
                :class="appearance === 'dark' ? 'ring-2 ring-red-500 border-red-500' : 'border-slate-300 dark:border-zinc-600'"
                class="flex items-center gap-3 px-4 py-3 bg-white dark:bg-zinc-700 border rounded-lg transition-all hover:border-red-300 dark:hover:border-red-600"
            >
                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                </svg>
                <span class="font-medium text-slate-900 dark:text-white">{{ __('Dark') }}</span>
            </button>

            <!-- System Mode -->
            <button
                type="button"
                @click="$wire.updateAppearance('system')"
                :class="appearance === 'system' ? 'ring-2 ring-red-500 border-red-500' : 'border-slate-300 dark:border-zinc-600'"
                class="flex items-center gap-3 px-4 py-3 bg-white dark:bg-zinc-700 border rounded-lg transition-all hover:border-red-300 dark:hover:border-red-600"
            >
                <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <span class="font-medium text-slate-900 dark:text-white">{{ __('System') }}</span>
            </button>
        </div>
    </div>
</div>
