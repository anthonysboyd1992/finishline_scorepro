<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>{{ $title ?? config('app.name') }}</title>

<link rel="icon" href="/favicon.ico" sizes="any">
<link rel="icon" href="/favicon.svg" type="image/svg+xml">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

{{-- Apply theme before paint to prevent flash --}}
<script>
    (function() {
        const theme = localStorage.getItem('theme');
        if (theme === 'light') {
            document.documentElement.classList.remove('dark');
        } else {
            document.documentElement.classList.add('dark');
        }
    })();
</script>

@vite(['resources/css/app.css', 'resources/js/app.js'])
@livewireStyles

<style>
    [x-cloak] { display: none !important; }
</style>
