<x-layouts::app :title="__('Dashboard')">
    <div class="p-4 md:p-6 lg:p-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl md:text-3xl font-bold text-slate-900 dark:text-white">{{ __('Welcome Back') }}</h1>
            <p class="text-slate-600 dark:text-slate-400 mt-1">{{ __('Select a module to get started') }}</p>
        </div>

        <!-- Module Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
            <!-- Season Management -->
            <a href="{{ route('seasons.index') }}" wire:navigate class="group relative bg-white dark:bg-zinc-800 rounded-2xl p-6 shadow-sm hover:shadow-lg transition-all duration-200 border border-slate-200 dark:border-zinc-700 hover:border-red-300 dark:hover:border-red-600">
                <div class="flex flex-col items-center text-center">
                    <div class="w-16 h-16 md:w-20 md:h-20 bg-red-500 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-red-600 transition-colors">
                        <svg class="w-8 h-8 md:w-10 md:h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-1">{{ __('Season Management') }}</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Manage racing seasons and events') }}</p>
                </div>
            </a>

            <!-- Race Management -->
            <a href="{{ route('race-management.index') }}" wire:navigate class="group relative bg-white dark:bg-zinc-800 rounded-2xl p-6 shadow-sm hover:shadow-lg transition-all duration-200 border border-slate-200 dark:border-zinc-700 hover:border-red-300 dark:hover:border-red-600">
                <div class="flex flex-col items-center text-center">
                    <div class="w-16 h-16 md:w-20 md:h-20 bg-red-500 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-red-600 transition-colors">
                        <svg class="w-8 h-8 md:w-10 md:h-10 text-white" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M4 4v16h2V4H4zm4 0v4h2v4h2V8h2v4h2V8h2v4h2V4H8zm0 8v4h2v-4H8zm4 0v4h2v-4h-2zm4 0v4h2v-4h-2zm-8 4v4h2v-4H8zm4 0v4h2v-4h-2zm4 0v4h2v-4h-2z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-1">{{ __('Race Management') }}</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Configure and run races') }}</p>
                </div>
            </a>

            <!-- Transponders -->
            <a href="{{ route('transponders.index') }}" wire:navigate class="group relative bg-white dark:bg-zinc-800 rounded-2xl p-6 shadow-sm hover:shadow-lg transition-all duration-200 border border-slate-200 dark:border-zinc-700 hover:border-red-300 dark:hover:border-red-600">
                <div class="flex flex-col items-center text-center">
                    <div class="w-16 h-16 md:w-20 md:h-20 bg-red-500 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-red-600 transition-colors">
                        <svg class="w-8 h-8 md:w-10 md:h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-1">{{ __('Transponder Club List') }}</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Alias lookup for club transponders') }}</p>
                </div>
            </a>

            <!-- Settings -->
            <a href="{{ route('profile.edit') }}" wire:navigate class="group relative bg-white dark:bg-zinc-800 rounded-2xl p-6 shadow-sm hover:shadow-lg transition-all duration-200 border border-slate-200 dark:border-zinc-700 hover:border-red-300 dark:hover:border-red-600">
                <div class="flex flex-col items-center text-center">
                    <div class="w-16 h-16 md:w-20 md:h-20 bg-red-500 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-red-600 transition-colors">
                        <svg class="w-8 h-8 md:w-10 md:h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-1">{{ __('Settings') }}</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Configure app preferences') }}</p>
                </div>
            </a>
        </div>
    </div>
</x-layouts::app>
