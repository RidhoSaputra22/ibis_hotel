@props([
    'sidebar' => [],
    'hotelName' => 'Ibis Makassar City Center',
    'systemName' => 'Restaurant & Cashier System',
    'username' => null,
    'theme' => 'light',
])

@php
    $isDark = $theme === 'dark';
    $resolvedUsername = filled($username) ? $username : session('cashier_login.display_name', 'ADHA');
@endphp

<div class="min-h-screen {{ $isDark ? 'bg-[radial-gradient(circle_at_75%_3%,rgba(23,74,106,.25),transparent_28%),linear-gradient(120deg,#06111d_0%,#092036_45%,#06121f_100%)]' : 'bg-[linear-gradient(135deg,#dbe6e9_0%,#f8fbfc_53%,#d8e4e7_100%)]' }}">
    <x-ui.app-sidebar :items="$sidebar" />

    <main class="ml-[58px] min-h-screen">
        <x-ui.app-header
            :hotel-name="$hotelName"
            :system-name="$systemName"
            :username="$resolvedUsername"
            :dark="$isDark"
        />

        {{ $slot }}
    </main>
</div>
