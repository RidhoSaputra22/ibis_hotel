@php
    $master = [
        ['label' => 'Service Category 1', 'icon' => 'footprints'],
        ['label' => 'Outlet Master', 'icon' => 'store'],
        ['label' => 'Service Category 2', 'icon' => 'bottle-wine'],
        ['label' => 'Service Table', 'icon' => 'table-properties'],
        ['label' => 'Outlet Type', 'icon' => 'panels-top-left'],
    ];

    $transaction = [
        ['label' => 'Open Cashier', 'icon' => 'wallet-cards', 'modal' => 'cashierLoginModal'],
        ['label' => 'Retail Transaction', 'icon' => 'store'],
        ['label' => 'Service Request / From Guest', 'icon' => 'clipboard-list'],
        ['label' => 'Folio Fast Check', 'icon' => 'receipt-text'],
        ['label' => 'F&B Point Desk', 'icon' => 'users-round'],
        ['label' => 'Restaurant Transaction', 'icon' => 'utensils', 'href' => url('/restaurant-transaction')],
        ['label' => 'Customer Relation & Management', 'icon' => 'monitor-smartphone'],
        ['label' => 'Non Revenue Folio Transaction', 'icon' => 'file-text'],
        ['label' => 'Facility Reservation', 'icon' => 'calendar-check-2'],
        ['label' => 'Folio Guest Record', 'icon' => 'notebook-pen'],
        ['label' => 'Folio Transaction', 'icon' => 'files'],
    ];

    $batch = [['label' => 'Soft Close A/R Period', 'icon' => 'book-open-check']];

    $reports = [
        ['label' => 'Detail Cash Cashier', 'icon' => 'contact-round'],
        ['label' => 'Package Service Daily Forecast', 'icon' => 'user-round-check'],
        ['label' => 'Cash Out', 'icon' => 'banknote-arrow-down'],
        ['label' => 'Point Refund', 'icon' => 'hand-coins'],
        ['label' => 'Daily Cashier Summary', 'icon' => 'notebook-tabs', 'href' => url('/daily-cashier-summary')],
        ['label' => 'Credit Card Transaction', 'icon' => 'credit-card'],
        ['label' => 'Vip Guest List', 'icon' => 'clipboard-check'],
        ['label' => 'Print Outlet Bill', 'icon' => 'printer'],
        ['label' => 'Daily Outlet Report', 'icon' => 'store'],
        ['label' => 'Bill/Ref. Cashier', 'icon' => 'receipt'],
        ['label' => 'Debit Card Transaction', 'icon' => 'landmark'],
        ['label' => 'Guest In House', 'icon' => 'house'],
        ['label' => 'Unpaid Bill Report', 'icon' => 'file-warning'],
        ['label' => 'Daily Rebate Record', 'icon' => 'badge-percent'],
        ['label' => 'Daily Cashier Record', 'icon' => 'clipboard-list'],
        ['label' => 'Sales Analysis', 'icon' => 'chart-no-axes-combined'],
        ['label' => 'City Ledger Transfer', 'icon' => 'files'],
        ['label' => 'Service Price List', 'icon' => 'badge-dollar-sign'],
        ['label' => 'Taking Over Charges', 'icon' => 'clipboard-pen-line'],
        ['label' => 'Daily Cashier Record', 'icon' => 'badge-check'],
        ['label' => 'Outlet Payment', 'icon' => 'wallet'],
        ['label' => 'Sales Summary', 'icon' => 'bar-chart-3'],
    ];
@endphp

<x-layouts.app title="FB & Shop Cashier">
    <x-layouts.legacy-page
        :sidebar="$sidebar"
        hotel-name="Ibis Makassar City Center"
        system-name="Front Office System"
        username="ADHA"
        theme="dark"
    >
        <x-dashboard.cashier-menu-grid :master="$master" :transaction="$transaction" :batch="$batch" :reports="$reports" />
    </x-layouts.legacy-page>

    <x-modals.cashier-login-modal id="cashierLoginModal" />
</x-layouts.app>
