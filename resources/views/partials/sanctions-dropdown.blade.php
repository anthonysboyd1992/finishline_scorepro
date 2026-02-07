@php
$allSanctions = \App\Models\Sanction::query()->orderBy('name')->get();
$currentSanctionId = session('current_sanction_id');
$currentSanctionModel = $currentSanctionId
    ? $allSanctions->firstWhere('id', $currentSanctionId)
    : $allSanctions->first();

if ($currentSanctionModel && !$currentSanctionId) {
    session(['current_sanction_id' => $currentSanctionModel->id]);
}
@endphp

@if($allSanctions->count() > 0)
    <!-- Sanction Dropdown -->
    <div 
        x-data="{ open: false }" 
        @keydown.escape.window="open = false"
        class="relative"
    >
        <button 
            @click="open = !open" 
            class="flex items-center gap-2 rounded-md px-3 py-1.5 bg-white/10 hover:bg-white/20 transition-colors"
        >
            <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
            </svg>
            <span class="text-sm font-medium text-white max-w-[150px] sm:max-w-[200px] truncate">{{ $currentSanctionModel?->name ?? 'Select Sanction' }}</span>
            <svg class="w-4 h-4 text-white/60 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>

        <!-- Dropdown Menu -->
        <div 
            x-show="open" 
            @click.away="open = false"
            x-transition
            x-cloak
            class="absolute left-0 mt-2 w-64 origin-top-left rounded-lg bg-white dark:bg-zinc-800 shadow-lg ring-1 ring-black/5 dark:ring-white/10 z-50"
        >
            <div class="p-2">
                <div class="px-3 py-2 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                    {{ __('Switch Sanction') }}
                </div>
                @foreach($allSanctions as $sanction)
                    <form method="POST" action="{{ route('sanction.switch', $sanction) }}">
                        @csrf
                        <button 
                            type="submit"
                            class="w-full flex items-center gap-3 px-3 py-2 text-sm rounded-md transition-colors {{ $currentSanctionModel?->id === $sanction->id ? 'bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-zinc-700' }}"
                        >
                            <svg class="w-4 h-4 shrink-0 {{ $currentSanctionModel?->id === $sanction->id ? 'text-red-500' : 'text-amber-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                            </svg>
                            <div class="flex-1 min-w-0 text-left">
                                <p class="font-medium truncate">{{ $sanction->name }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">{{ ucfirst($sanction->type) }}</p>
                            </div>
                            @if($currentSanctionModel?->id === $sanction->id)
                                <svg class="w-4 h-4 text-red-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            @endif
                        </button>
                    </form>
                @endforeach
            </div>
        </div>
    </div>
@endif
