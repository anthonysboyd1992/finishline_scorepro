<?php

use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::settings')] class extends Component {
    //
}; ?>

<div class="space-y-6">
    <!-- Page Header -->
    <div>
        <h1 class="text-2xl font-bold text-white">{{ __('Appearance') }}</h1>
        <p class="mt-1 text-sm text-slate-400">{{ __('Appearance settings for your account') }}</p>
    </div>

    <div class="bg-zinc-800 rounded-xl border border-zinc-700 p-6">
        <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
            </svg>
            <div>
                <p class="font-medium text-white">{{ __('Dark Mode') }}</p>
                <p class="text-sm text-slate-400">{{ __('This application uses dark mode for optimal readability.') }}</p>
            </div>
        </div>
    </div>
</div>
