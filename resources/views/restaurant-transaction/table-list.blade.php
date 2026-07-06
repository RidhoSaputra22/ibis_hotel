@php
    $tables = collect(range(1, 20))->map(fn ($number) => str_pad($number, 2, '0', STR_PAD_LEFT));
@endphp

<x-layouts.app title="Table List">
    <x-layouts.legacy-page :sidebar="$sidebar" hotel-name="Ibis Makassar City Center" system-name="Restaurant & Cashier System" :username="session('cashier_login.display_name', 'ADHA')">
        <x-ui.breadcrumb-tabs :items="[
            ['label' => 'Open Cashier', 'href' => route('cashier.session.create')],
            ['label' => 'FB & Shop Cashier', 'href' => route('cashier.dashboard')],
            ['label' => 'Restaurant Transaction', 'href' => route('restaurant.transaction')],
            ['label' => 'TABLE LIST', 'current' => true],
        ]" />
        <x-restaurant.table-list-panel id="restaurantTableList" :tables="$tables" />
    </x-layouts.legacy-page>
</x-layouts.app>
