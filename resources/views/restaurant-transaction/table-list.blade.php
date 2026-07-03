@php
    $tables = collect(range(1, 20))->map(fn ($number) => str_pad($number, 2, '0', STR_PAD_LEFT));
@endphp

<x-layouts.app title="Table List">
    <x-layouts.legacy-page :sidebar="$sidebar" hotel-name="Ibis Makassar City Center" system-name="Restaurant & Cashier System" :username="session('cashier_login.display_name', 'ADHA')">
        <x-ui.breadcrumb-tabs :items="[
            ['label' => 'Open Cashier', 'modal' => 'cashierLoginModal'],
            ['label' => 'FB & Shop Cashier'],
            ['label' => 'Restaurant Transaction', 'href' => url('/restaurant-transaction')],
            ['label' => 'TABLE LIST', 'current' => true],
        ]" />
        <x-restaurant.table-list-panel id="restaurantTableList" :tables="$tables" />
    </x-layouts.legacy-page>

    <x-modals.cashier-login-modal id="cashierLoginModal" />
</x-layouts.app>
