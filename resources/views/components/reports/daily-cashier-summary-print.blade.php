<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Jika Tailwind sudah memakai Vite, aktifkan baris berikut --}}
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    <title>Daily Cashier Summary Print</title>
</head>

<body class="min-h-screen bg-[#cfdde1] font-sans text-[#455d66]">

@php
    $reportDate = '15-Jun-2026';
    $cashier = 'FBA-DHA - FBADHA';

    $rows = [
        [
            'outlet' => 'Ibis Kitchen',
            'transaction' => 1,
            'total_sales' => 20000,
            'cash' => 20000,
            'debit_card' => 0,
            'credit_card' => 0,
            'city_ledger' => 0,
            'folio' => 0,
            'deposit' => 0,
            'voucher' => 0,
            'house_use' => 0,
            'entertain' => 0,
            'complimentary' => 0,
            'tips' => 0,
            'discount' => 0,
        ],
    ];

    $totalTransaction = collect($rows)->sum('transaction');
    $totalSales = collect($rows)->sum('total_sales');
    $totalCash = collect($rows)->sum('cash');
    $totalDebit = collect($rows)->sum('debit_card');
    $totalCredit = collect($rows)->sum('credit_card');
    $totalCityLedger = collect($rows)->sum('city_ledger');
    $totalFolio = collect($rows)->sum('folio');
    $totalDeposit = collect($rows)->sum('deposit');
    $totalVoucher = collect($rows)->sum('voucher');
    $totalHouseUse = collect($rows)->sum('house_use');
    $totalEntertain = collect($rows)->sum('entertain');
    $totalComplimentary = collect($rows)->sum('complimentary');
    $totalTips = collect($rows)->sum('tips');
    $totalDiscount = collect($rows)->sum('discount');
@endphp

<div class="min-h-screen bg-[linear-gradient(135deg,#c9d9de_0%,#eef5f6_52%,#cbd9dd_100%)]">

    {{-- Header Viewer --}}
    <header class="flex h-11 items-center justify-between border-b border-[#93aab1] bg-[linear-gradient(180deg,#edf5f7_0%,#cfdee3_100%)] px-3 shadow-sm">
        <div class="flex items-center gap-2">
            <div class="grid h-5 w-5 place-items-center rounded-[2px] border border-[#6f98a6] bg-[#d9eff4] text-[9px] font-black text-[#2c687f]">
                R
            </div>

            <span class="text-[11px] font-bold text-[#4f6872]">
                Daily Cashier Summary
            </span>
        </div>

        <div class="flex items-center gap-3 text-[#59717b]">
            <button title="Minimize" class="hover:text-[#1e6e8d]">
                <i data-lucide="minus" class="h-3.5 w-3.5"></i>
            </button>

            <button title="Maximize" class="hover:text-[#1e6e8d]">
                <i data-lucide="square" class="h-3 w-3"></i>
            </button>

            <button title="Close" class="hover:text-rose-600">
                <i data-lucide="x" class="h-4 w-4"></i>
            </button>
        </div>
    </header>

    {{-- Toolbar --}}
    <div class="flex h-10 items-center gap-1 border-b border-[#a8bcc2] bg-[#e6f0f2] px-3">
        <button class="toolbar-button" title="Export">
            <i data-lucide="download" class="h-3.5 w-3.5"></i>
        </button>

        <button class="toolbar-button" title="Print" onclick="window.print()">
            <i data-lucide="printer" class="h-3.5 w-3.5"></i>
        </button>

        <button class="toolbar-button" title="Toggle Group Tree">
            <i data-lucide="panel-left" class="h-3.5 w-3.5"></i>
        </button>

        <span class="mx-2 h-5 border-l border-[#aec0c5]"></span>

        <button class="toolbar-button" title="First Page">
            <i data-lucide="chevrons-left" class="h-3.5 w-3.5"></i>
        </button>

        <button class="toolbar-button" title="Previous Page">
            <i data-lucide="chevron-left" class="h-3.5 w-3.5"></i>
        </button>

        <button class="toolbar-button" title="Next Page">
            <i data-lucide="chevron-right" class="h-3.5 w-3.5"></i>
        </button>

        <button class="toolbar-button" title="Last Page">
            <i data-lucide="chevrons-right" class="h-3.5 w-3.5"></i>
        </button>

        <input
            type="text"
            value="1"
            class="ml-2 h-6 w-20 border border-[#aabcc2] bg-white px-2 text-[10px] text-[#546f78] outline-none"
        >

        <span class="text-[10px] text-[#617982]">/ 1</span>

        <button class="toolbar-button ml-2" title="Find">
            <i data-lucide="binoculars" class="h-3.5 w-3.5"></i>
        </button>

        <button class="toolbar-button" title="Zoom">
            <i data-lucide="search" class="h-3.5 w-3.5"></i>
        </button>

        <div class="ml-auto text-[10px] font-bold text-[#6b8087]">
            SAP CRYSTAL REPORTS
        </div>
    </div>

    <div class="grid min-h-[calc(100vh-81px)] grid-cols-[205px_1fr]">

        {{-- Sidebar Tree --}}
        <aside class="border-r border-[#aabcc1] bg-[#eaf2f4]">
            <div class="border-b border-[#bdcdd2] px-3 py-2 text-[10px] font-bold text-[#607981]">
                <div class="flex items-center gap-1.5">
                    <i data-lucide="folder-open" class="h-3.5 w-3.5 text-[#41788c]"></i>
                    <span>Adha (1)</span>
                </div>
            </div>

            <button
                type="button"
                class="flex w-full items-center gap-2 border-b border-[#d8e3e6] bg-[#dbeef3] px-4 py-2 text-left text-[10px] font-semibold text-[#356d82]"
            >
                <i data-lucide="file-text" class="h-3.5 w-3.5"></i>
                Main Report
            </button>
        </aside>

        {{-- Main Report Area --}}
        <main class="min-w-0 bg-[#d9e6e9] p-3">
            <div class="flex h-8 items-end">
                <div class="rounded-t-sm border border-b-0 border-[#afc1c6] bg-[#edf5f6] px-4 py-2 text-[10px] font-bold text-[#526d76]">
                    Main Report
                </div>
            </div>

            <section class="h-[calc(100vh-125px)] overflow-auto border border-[#97afb6] bg-[#cddade] p-4 shadow-inner">

                {{-- Kertas Laporan --}}
                <div
                    id="reportPaper"
                    class="mx-auto min-w-[1120px] max-w-[1200px] bg-white px-6 py-7 text-[10px] text-[#4d626a] shadow-[0_2px_10px_rgba(15,23,42,.25)]"
                >
                    {{-- Header Laporan --}}
                    <div class="grid grid-cols-[190px_1fr_230px] items-start gap-5">
                        <div>
                            <div class="flex h-[78px] w-[88px] flex-col items-center justify-center bg-[#d9534f] text-white shadow-sm">
                                <span class="text-3xl font-black leading-none">ibis</span>
                                <span class="mt-1 text-[8px] tracking-wide">BRAND</span>
                            </div>

                            <p class="mt-2 w-[88px] text-center text-[8px] font-semibold text-[#6a7e85]">
                                Makassar City Center
                            </p>
                        </div>

                        <div class="pt-2 text-center">
                            <p class="text-sm font-semibold text-[#526972]">
                                Ibis Makassar City Center
                            </p>

                            <h1 class="mt-1 text-xl font-black tracking-tight text-[#435f69]">
                                Daily Cashier Summary
                            </h1>
                        </div>

                        <div class="pt-3 text-right text-[10px] leading-relaxed text-[#586f77]">
                            <p>Page 1 of 1 (PSR01200)</p>
                            <p>Print Date : 15-Jun-2026 12:07</p>
                            <p>Printed By : FBADHA</p>
                        </div>
                    </div>

                    {{-- Info Summary --}}
                    <div class="mt-4 grid grid-cols-[110px_1fr] gap-x-2 gap-y-1 border-b border-[#8fa6ad] pb-2 text-[11px]">
                        <span class="font-bold">Trans. Date :</span>
                        <span>{{ $reportDate }}</span>

                        <span class="font-bold">Cashier :</span>
                        <span>{{ $cashier }}</span>
                    </div>

                    {{-- Table --}}
                    <div class="mt-2 overflow-x-auto">
                        <table class="w-full min-w-[1120px] border-collapse text-[10px]">
                            <thead class="border-y border-[#7f989f] bg-[#eff5f6] font-bold text-[#526b74]">
                                <tr>
                                    <th rowspan="2" class="w-[170px] border-r border-[#b6c8cd] px-2 py-2 text-left">
                                        Outlet
                                    </th>

                                    <th rowspan="2" class="w-[72px] border-r border-[#b6c8cd] px-2 py-2 text-center">
                                        Total
                                    </th>

                                    <th rowspan="2" class="w-[86px] border-r border-[#b6c8cd] px-2 py-2 text-right">
                                        Total Sales
                                    </th>

                                    <th rowspan="2" class="w-[86px] border-r border-[#b6c8cd] px-2 py-2 text-right">
                                        Cash
                                    </th>

                                    <th rowspan="2" class="w-[86px] border-r border-[#b6c8cd] px-2 py-2 text-right">
                                        Debit Card
                                    </th>

                                    <th rowspan="2" class="w-[86px] border-r border-[#b6c8cd] px-2 py-2 text-right">
                                        Credit Card
                                    </th>

                                    <th rowspan="2" class="w-[86px] border-r border-[#b6c8cd] px-2 py-2 text-right">
                                        City Ledger
                                    </th>

                                    <th rowspan="2" class="w-[75px] border-r border-[#b6c8cd] px-2 py-2 text-right">
                                        Folio
                                    </th>

                                    <th rowspan="2" class="w-[75px] border-r border-[#b6c8cd] px-2 py-2 text-right">
                                        Deposit
                                    </th>

                                    <th rowspan="2" class="w-[75px] border-r border-[#b6c8cd] px-2 py-2 text-right">
                                        Voucher
                                    </th>

                                    <th rowspan="2" class="w-[78px] border-r border-[#b6c8cd] px-2 py-2 text-right">
                                        House Use
                                    </th>

                                    <th rowspan="2" class="w-[80px] border-r border-[#b6c8cd] px-2 py-2 text-right">
                                        Tips
                                    </th>

                                    <th rowspan="2" class="w-[80px] px-2 py-2 text-right">
                                        Discount
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                {{-- Header Cashier --}}
                                <tr class="border-b border-[#95aeb5]">
                                    <td colspan="13" class="px-2 py-2 text-[12px] font-bold text-[#4e666f]">
                                        Cashier : &nbsp; Adha (1)
                                    </td>
                                </tr>

                                @foreach ($rows as $row)
                                    <tr class="border-b border-[#c3d2d6]">
                                        <td class="border-r border-[#d5e0e3] px-2 py-2 font-semibold">
                                            {{ $row['outlet'] }}
                                        </td>

                                        <td class="border-r border-[#d5e0e3] px-2 py-2 text-center">
                                            <span class="font-bold text-[#2c7892]">#</span>
                                            {{ $row['transaction'] }}
                                        </td>

                                        <td class="border-r border-[#d5e0e3] px-2 py-2 text-right">
                                            {{ number_format($row['total_sales'], 2) }}
                                        </td>

                                        <td class="border-r border-[#d5e0e3] px-2 py-2 text-right">
                                            {{ number_format($row['cash'], 2) }}
                                        </td>

                                        <td class="border-r border-[#d5e0e3] px-2 py-2 text-right">
                                            {{ number_format($row['debit_card'], 2) }}
                                        </td>

                                        <td class="border-r border-[#d5e0e3] px-2 py-2 text-right">
                                            {{ number_format($row['credit_card'], 2) }}
                                        </td>

                                        <td class="border-r border-[#d5e0e3] px-2 py-2 text-right">
                                            {{ number_format($row['city_ledger'], 2) }}
                                        </td>

                                        <td class="border-r border-[#d5e0e3] px-2 py-2 text-right">
                                            {{ number_format($row['folio'], 2) }}
                                        </td>

                                        <td class="border-r border-[#d5e0e3] px-2 py-2 text-right">
                                            {{ number_format($row['deposit'], 2) }}
                                        </td>

                                        <td class="border-r border-[#d5e0e3] px-2 py-2 text-right">
                                            {{ number_format($row['voucher'], 2) }}
                                        </td>

                                        <td class="border-r border-[#d5e0e3] px-2 py-2 text-right">
                                            {{ number_format($row['house_use'], 2) }}
                                        </td>

                                        <td class="border-r border-[#d5e0e3] px-2 py-2 text-right">
                                            {{ number_format($row['tips'], 2) }}
                                        </td>

                                        <td class="px-2 py-2 text-right">
                                            {{ number_format($row['discount'], 2) }}
                                        </td>
                                    </tr>

                                    <tr class="border-b border-[#95aeb5]">
                                        <td class="px-2 py-1.5 text-right font-semibold text-[#586f77]">
                                            Total Non Sales
                                        </td>

                                        <td class="px-2 py-1.5 text-center">0</td>
                                        <td class="px-2 py-1.5 text-right">0.00</td>
                                        <td class="px-2 py-1.5 text-right">0.00</td>
                                        <td class="px-2 py-1.5 text-right">0.00</td>
                                        <td class="px-2 py-1.5 text-right">0.00</td>
                                        <td class="px-2 py-1.5 text-right">0.00</td>
                                        <td class="px-2 py-1.5 text-right">0.00</td>
                                        <td class="px-2 py-1.5 text-right">0.00</td>
                                        <td class="px-2 py-1.5 text-right">0.00</td>
                                        <td class="px-2 py-1.5 text-right">0.00</td>
                                        <td class="px-2 py-1.5 text-right">0.00</td>
                                        <td class="px-2 py-1.5 text-right">0.00</td>
                                    </tr>
                                @endforeach

                                {{-- Total Cashier --}}
                                <tr class="border-b border-[#7e989f]">
                                    <td class="px-2 py-2 font-bold">
                                        Total
                                    </td>

                                    <td class="px-2 py-2 text-center font-bold">
                                        <span class="text-[#2c7892]">#</span>
                                        {{ $totalTransaction }}
                                    </td>

                                    <td class="px-2 py-2 text-right font-bold">
                                        {{ number_format($totalSales, 2) }}
                                    </td>

                                    <td class="px-2 py-2 text-right font-bold">
                                        {{ number_format($totalCash, 2) }}
                                    </td>

                                    <td class="px-2 py-2 text-right font-bold">
                                        {{ number_format($totalDebit, 2) }}
                                    </td>

                                    <td class="px-2 py-2 text-right font-bold">
                                        {{ number_format($totalCredit, 2) }}
                                    </td>

                                    <td class="px-2 py-2 text-right font-bold">
                                        {{ number_format($totalCityLedger, 2) }}
                                    </td>

                                    <td class="px-2 py-2 text-right font-bold">
                                        {{ number_format($totalFolio, 2) }}
                                    </td>

                                    <td class="px-2 py-2 text-right font-bold">
                                        {{ number_format($totalDeposit, 2) }}
                                    </td>

                                    <td class="px-2 py-2 text-right font-bold">
                                        {{ number_format($totalVoucher, 2) }}
                                    </td>

                                    <td class="px-2 py-2 text-right font-bold">
                                        {{ number_format($totalHouseUse, 2) }}
                                    </td>

                                    <td class="px-2 py-2 text-right font-bold">
                                        {{ number_format($totalTips, 2) }}
                                    </td>

                                    <td class="px-2 py-2 text-right font-bold">
                                        {{ number_format($totalDiscount, 2) }}
                                    </td>
                                </tr>

                                {{-- Grand Total --}}
                                <tr class="border-b border-[#738f98] bg-[#f3f8f9]">
                                    <td class="px-2 py-3 text-[12px] font-black">
                                        Grand Total
                                    </td>

                                    <td class="px-2 py-3 text-center font-black">
                                        <span class="text-[#2c7892]">#</span>
                                        {{ $totalTransaction }}
                                    </td>

                                    <td class="px-2 py-3 text-right font-black">
                                        {{ number_format($totalSales, 2) }}
                                    </td>

                                    <td class="px-2 py-3 text-right font-black">
                                        {{ number_format($totalCash, 2) }}
                                    </td>

                                    <td class="px-2 py-3 text-right font-black">
                                        {{ number_format($totalDebit, 2) }}
                                    </td>

                                    <td class="px-2 py-3 text-right font-black">
                                        {{ number_format($totalCredit, 2) }}
                                    </td>

                                    <td class="px-2 py-3 text-right font-black">
                                        {{ number_format($totalCityLedger, 2) }}
                                    </td>

                                    <td class="px-2 py-3 text-right font-black">
                                        {{ number_format($totalFolio, 2) }}
                                    </td>

                                    <td class="px-2 py-3 text-right font-black">
                                        {{ number_format($totalDeposit, 2) }}
                                    </td>

                                    <td class="px-2 py-3 text-right font-black">
                                        {{ number_format($totalVoucher, 2) }}
                                    </td>

                                    <td class="px-2 py-3 text-right font-black">
                                        {{ number_format($totalHouseUse, 2) }}
                                    </td>

                                    <td class="px-2 py-3 text-right font-black">
                                        {{ number_format($totalTips, 2) }}
                                    </td>

                                    <td class="px-2 py-3 text-right font-black">
                                        {{ number_format($totalDiscount, 2) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </main>
    </div>
</div>

<style>
    .toolbar-button {
        display: grid;
        height: 25px;
        width: 27px;
        place-items: center;
        border: 1px solid transparent;
        color: #59737d;
        transition: .15s ease;
    }

    .toolbar-button:hover {
        border-color: #89aab4;
        background: #f5fafb;
        color: #26718c;
    }

    @media print {
        body * {
            visibility: hidden;
        }

        #reportPaper,
        #reportPaper * {
            visibility: visible;
        }

        #reportPaper {
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            max-width: none;
            min-width: 0;
            box-shadow: none;
        }
    }
</style>

<script>
    lucide.createIcons({
        attrs: {
            'stroke-width': 1.8
        }
    });
</script>

</body>
</html>
