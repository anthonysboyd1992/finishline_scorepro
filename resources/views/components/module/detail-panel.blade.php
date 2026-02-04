@props([
    'emptyMessage' => 'Please select an item',
    'emptyLinkText' => 'new item',
    'emptyLinkHref' => '#',
])

<div class="flex-1 bg-slate-100 dark:bg-zinc-900 overflow-y-auto">
    @if($slot->isEmpty())
        <!-- Empty State -->
        <div class="flex h-full items-center justify-center">
            <p class="text-slate-600 dark:text-slate-400 text-center">
                {{ $emptyMessage }} or 
                <a href="{{ $emptyLinkHref }}" class="text-blue-600 dark:text-blue-400 hover:underline">{{ $emptyLinkText }}</a>.
            </p>
        </div>
    @else
        {{ $slot }}
    @endif
</div>
