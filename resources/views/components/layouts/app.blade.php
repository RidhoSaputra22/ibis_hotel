@props([
    'title' => config('app.name', 'Ibis Hotel Management System'),
    'bodyClass' => 'min-h-screen bg-slate-100 font-sans text-slate-700 antialiased',
])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('style')

    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="{{ $bodyClass }}">
    {{ $slot }}

    @stack('scripts')

    <script>
        document.addEventListener('click', (event) => {
            const trigger = event.target.closest('[data-open-dialog]');
            if (!trigger) return;

            const dialog = document.getElementById(trigger.dataset.openDialog);
            if (dialog && typeof dialog.showModal === 'function') {
                dialog.showModal();
            }
        });

        document.addEventListener('DOMContentLoaded', () => {
            window.lucide?.createIcons({ attrs: { 'stroke-width': 1.8 } });
        });
    </script>
</body>
</html>
