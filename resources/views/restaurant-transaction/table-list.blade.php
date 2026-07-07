<x-layouts.app title="Table List">
    <x-layouts.legacy-page :sidebar="$sidebar" hotel-name="Ibis Makassar City Center" system-name="Restaurant & Cashier System" :username="session('cashier_login.display_name', 'ADHA')">
        <x-ui.breadcrumb-tabs :items="[
            ['label' => 'Open Cashier', 'href' => route('cashier.session.create')],
            ['label' => 'FB & Shop Cashier', 'href' => route('cashier.dashboard')],
            ['label' => 'Restaurant Transaction'],
            ['label' => 'TABLE LIST', 'current' => true],
        ]" />

        <x-restaurant.table-list-panel
            id="restaurantTableList"
            :tables="$tables"
            :selected-table="$selectedTable"
            :transaction-url="route('restaurant.transaction')"
        />
    </x-layouts.legacy-page>
</x-layouts.app>
