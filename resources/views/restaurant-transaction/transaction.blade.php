@php
    $zones = range('A', 'J');
    $serviceCategories = ['Appetizer', 'Buffet', 'Dessert', 'Vegetarian', 'Food Promotion', 'GRAB FOOD', 'Nusantara Food'];
    $resolvedSelectedTable = $selectedTable ?? '01';
    $tableListUrl = route('restaurant.table-list', ['selected_table' => $resolvedSelectedTable]);
    $serviceItems = [
        ['name' => 'Gado-Gado', 'price' => 35000, 'category' => 'Appetizer'],
        ['name' => 'Spring Roll', 'price' => 30000, 'category' => 'Appetizer'],
        ['name' => 'Chicken Satay', 'price' => 55000, 'category' => 'Appetizer'],
        ['name' => 'Nasi Goreng Special', 'price' => 65000, 'category' => 'Nusantara Food'],
        ['name' => 'Mie Goreng', 'price' => 60000, 'category' => 'Nusantara Food'],
        ['name' => 'Soto Ayam', 'price' => 45000, 'category' => 'Nusantara Food'],
        ['name' => 'Caesar Salad', 'price' => 50000, 'category' => 'Vegetarian'],
        ['name' => 'French Fries', 'price' => 28000, 'category' => 'Food Promotion'],
        ['name' => 'Chocolate Cake', 'price' => 40000, 'category' => 'Dessert'],
        ['name' => 'Ice Tea', 'price' => 18000, 'category' => 'Buffet'],
        ['name' => 'Mineral Water', 'price' => 12000, 'category' => 'Buffet'],
        ['name' => 'Coffee Latte', 'price' => 35000, 'category' => 'Buffet'],
    ];
@endphp

<x-layouts.app title="Restaurant Transaction">
    <x-ui.legacy-style-kit />
    <x-layouts.legacy-page :sidebar="$sidebar" hotel-name="Ibis Makassar City Center" system-name="Restaurant & Cashier System" :username="session('cashier_login.display_name', 'ADHA')">
        <x-ui.breadcrumb-tabs :items="[
            ['label' => 'Open Cashier', 'href' => route('cashier.session.create')],
            ['label' => 'Restaurant Transaction', 'href' => $tableListUrl],
            ['label' => 'TABLE ' . $resolvedSelectedTable, 'current' => true, 'id' => 'transactionTableTab'],
        ]" />

        <section class="p-3 lg:p-4">
            <div class="min-h-[calc(100vh-120px)] overflow-hidden rounded-sm border border-slate-300 bg-[#f9fcfd] shadow-sm">
                <x-restaurant.transaction-toolbar :table-list-url="$tableListUrl" />
                <div class="grid min-h-[calc(100vh-182px)] grid-cols-1 xl:grid-cols-[64px_minmax(420px,1.16fr)_160px_minmax(350px,.96fr)]">
                    <x-restaurant.table-zone-selector :zones="$zones" />
                    <x-restaurant.order-information-form />
                    <x-restaurant.service-browser :categories="$serviceCategories" :items="$serviceItems" />
                </div>
            </div>
        </section>
    </x-layouts.legacy-page>

    <x-restaurant.waiter-modal id="waiterModal" />
    <x-modals.folio-lookup-modal id="folioLookupModal" />
    <x-modals.reservation-folio-lookup-modal id="reservationFolioLookupModal" />
    <x-modals.move-split-modal id="moveSplitModal" :table-no="$resolvedSelectedTable" order-no="00000017" />
    <x-modals.print-billing-modal id="printBillingModal" :table-no="$resolvedSelectedTable" check-no="0000024358" :cashier="session('cashier_login.display_name', 'ADHA')" :outlet="session('cashier_login.outlet', 'Ibis Kitchen')" />
    <x-modals.settle-modal id="settleModal" bill-no="0000024593" :total-amount="20000" />
    <x-restaurant.transaction-client :selected-table="$resolvedSelectedTable" />
</x-layouts.app>
