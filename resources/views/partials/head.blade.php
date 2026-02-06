<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="csrf-token" content="{{ csrf_token() }}" />

<title>{{ $title ?? config('app.name') }}</title>

<link rel="icon" href="/favicon.ico" sizes="any">
<link rel="icon" href="/favicon.svg" type="image/svg+xml">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

{{-- Apply theme before paint and after every Livewire SPA navigation --}}
<script data-navigate-once>
    (function() {
        // On first full page load, sync DB preference into localStorage
        var dbPref = @json(auth()->user()?->appearance ?? null);
        if (!localStorage.getItem('theme') && dbPref) {
            localStorage.setItem('theme', dbPref);
        }

        // Shared function to apply theme from localStorage
        function applyTheme() {
            var theme = localStorage.getItem('theme') || 'dark';
            if (theme === 'system') {
                var prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                document.documentElement.classList.toggle('dark', prefersDark);
            } else {
                document.documentElement.classList.toggle('dark', theme === 'dark');
            }
        }

        // Apply immediately (prevents flash on first load)
        applyTheme();

        // Re-apply after every Livewire SPA navigation (wire:navigate strips the class)
        document.addEventListener('livewire:navigated', applyTheme);

        // Also listen for system preference changes when in 'system' mode
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function() {
            if (localStorage.getItem('theme') === 'system') {
                applyTheme();
            }
        });
    })();
</script>

@vite(['resources/css/app.css', 'resources/js/app.js'])
@livewireStyles

<style>
    [x-cloak] { display: none !important; }
</style>
