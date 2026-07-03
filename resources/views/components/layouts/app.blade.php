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
        const openCashierLoginModal = (trigger) => {
            const dialog = document.getElementById(trigger.dataset.openCashierLogin);
            if (!dialog || typeof dialog.showModal !== 'function') {
                return;
            }

            const redirectTarget = trigger.dataset.redirectUrl || window.location.href;

            dialog.querySelectorAll('[data-login-redirect-input]').forEach((input) => {
                input.value = redirectTarget;
            });

            if (!dialog.open) {
                dialog.showModal();
            }
        };

        document.addEventListener('click', (event) => {
            const loginTrigger = event.target.closest('[data-open-cashier-login]');
            if (loginTrigger) {
                event.preventDefault();
                openCashierLoginModal(loginTrigger);
                return;
            }

            const trigger = event.target.closest('[data-open-dialog]');
            if (!trigger) return;

            const dialog = document.getElementById(trigger.dataset.openDialog);
            if (dialog && typeof dialog.showModal === 'function' && !dialog.open) {
                dialog.showModal();
            }
        });

        document.addEventListener('DOMContentLoaded', () => {
            window.lucide?.createIcons({ attrs: { 'stroke-width': 1.8 } });
        });
    </script>
</body>
</html>
