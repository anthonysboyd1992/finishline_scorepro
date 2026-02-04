@props([
    'title',
    'description',
])

<div class="flex w-full flex-col text-center">
    <h1 class="text-2xl font-semibold text-stone-900 dark:text-white mb-1">{{ $title }}</h1>
    <p class="text-sm text-stone-600 dark:text-stone-400">{{ $description }}</p>
</div>
