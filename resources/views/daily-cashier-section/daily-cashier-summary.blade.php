@php
    $sidebar = [
        ['label' => 'Favorites', 'icon' => 'star'],
        ['label' => 'Approval', 'icon' => 'circle-check'],
        ['label' => 'Banquet', 'icon' => 'calendar-days'],
        ['label' => 'Department Request', 'icon' => 'clipboard-list'],
        ['label' => 'FB & Shop Cashier', 'icon' => 'shopping-bag', 'active' => true],
        ['label' => 'Sales', 'icon' => 'badge-dollar-sign'],
    ];
    $outlets = [
        ['code' => '10', 'name' => 'Ibis Kitchen'],
        ['code' => '20', 'name' => 'Restaurant Terrace'],
        ['code' => '30', 'name' => 'Room Service'],
        ['code' => '40', 'name' => 'Banquet'],
        ['code' => '50', 'name' => 'Pool Bar'],
    ];
    $cashiers = [
        ['code' => 'ADHA', 'name' => 'Adha'],
        ['code' => 'FARHAN', 'name' => 'Farhan'],
        ['code' => 'NURUL', 'name' => 'Nurul'],
        ['code' => 'RIZKY', 'name' => 'Rizky'],
    ];
    $cashierLookupRows = collect($cashiers)->map(function ($cashier, $index) {
        return [
            'cashier_id' => $cashier['code'],
            'name' => $cashier['name'],
            'shift' => $index < 2 ? '1' : '2',
        ];
    })->all();
@endphp

<x-layouts.app title="Daily Cashier Summary">
    <x-ui.legacy-style-kit />
    <x-layouts.legacy-page :sidebar="$sidebar" hotel-name="Ibis Makassar City Center" system-name="Front Office & Cashier System" username="ADHA">
        <x-ui.breadcrumb-tabs :items="[
            ['label' => 'Open Cashier', 'modal' => 'cashierLoginModal'],
            ['label' => 'Restaurant Transaction', 'href' => url('/restaurant-transaction')],
            ['label' => 'Daily Cashier Summary', 'current' => true],
        ]" />

        <section class="p-4 lg:p-5">
            <div class="min-h-[calc(100vh-125px)] overflow-hidden rounded-sm border border-slate-300 bg-[#f8fbfc] shadow-sm">
                <div class="border-b border-slate-200 bg-[#eff5f6] px-4 py-3"><h1 class="text-sm font-bold text-[#536d76]">Daily Cashier Summary</h1></div>
                <div class="grid min-h-[calc(100vh-179px)] grid-cols-1 lg:grid-cols-[550px_1fr]">
                    <x-daily-cashier.summary-filter :outlets="$outlets" :cashiers="$cashiers" />
                    <x-daily-cashier.summary-report />
                </div>
            </div>
        </section>
    </x-layouts.legacy-page>

    <x-daily-cashier.printer-properties-modal />
    <x-modals.cashier-login-modal id="cashierLoginModal" />
    <x-modals.cashier-lookup-modal id="cashierLookupModal" :cashiers="$cashierLookupRows" />
    <x-daily-cashier.summary-client :outlets="$outlets" :cashiers="$cashiers" />

    @push('style')
        <style>
            @media print {
                body * { visibility: hidden; }
                #reportContainer, #reportContainer * { visibility: visible; }
                #reportContainer { position: fixed; inset: 0; display: block !important; width: 100%; background: white; }
                #browserPrintButton { display: none; }
            }
        </style>
    @endpush
</x-layouts.app>
