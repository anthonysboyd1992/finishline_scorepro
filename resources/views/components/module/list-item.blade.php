@props([
    'href' => '#',
    'active' => false,
])

<a 
    href="{{ $href }}"
    {{ $attributes->merge(['class' => 'block px-4 py-3 border-b border-slate-200 dark:border-zinc-700 hover:bg-slate-50 dark:hover:bg-zinc-700 transition-colors ' . ($active ? 'bg-blue-50 dark:bg-zinc-700 border-l-4 border-l-blue-500' : '')]) }}
>
    {{ $slot }}
</a>
