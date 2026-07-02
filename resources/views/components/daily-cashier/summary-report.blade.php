<section class="relative min-h-[500px] bg-white">
    <div id="emptyReportState" class="absolute inset-0 grid place-items-center p-8">
        <div class="max-w-sm text-center">
            <div class="mx-auto grid h-14 w-14 place-items-center rounded-full border border-[#d6e4e7] bg-[#f3f8f9] text-[#6c8993]"><i data-lucide="file-chart-column" class="h-6 w-6"></i></div>
            <h2 class="mt-4 text-sm font-bold text-[#6d838b]">Daily Cashier Summary</h2>
            <p class="mt-2 text-xs leading-relaxed text-[#87989d]">Atur parameter laporan pada panel kiri, lalu tekan tombol Print untuk menampilkan ringkasan transaksi harian.</p>
        </div>
    </div>

    <div id="reportContainer" class="hidden p-6">
        <div class="mx-auto max-w-4xl">
            <div class="flex flex-wrap items-start justify-between gap-4 border-b border-slate-200 pb-5">
                <div>
                    <p class="text-xs font-bold tracking-wide text-[#315f72]">IBIS MAKASSAR CITY CENTER</p>
                    <h2 class="mt-1 text-lg font-black text-[#435f69]">Daily Cashier Summary</h2>
                    <p id="reportMeta" class="mt-1 text-[11px] text-[#71868d]"></p>
                </div>
                <button id="browserPrintButton" type="button" class="small-button"><i data-lucide="printer" class="h-3.5 w-3.5"></i> Browser Print</button>
            </div>
            <div class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-3">
                <div class="report-stat"><span>Total Group</span><strong id="reportCashierCount">0</strong></div>
                <div class="report-stat"><span>Total Transaction</span><strong id="reportTransactionCount">0</strong></div>
                <div class="report-stat"><span>Total Sales</span><strong id="reportTotalSales">Rp0</strong></div>
            </div>
            <x-tables.legacy-table-shell wrapper-class="mt-5 overflow-x-auto rounded-sm border border-[#b9cbd1]" table-class="min-w-[680px] w-full border-collapse text-left text-[11px]">
                    <thead class="bg-[#dcecf0] text-[#536d76]"><tr class="border-b border-[#b7cbd1]"><th class="border-r border-[#c7d7db] px-3 py-2 font-bold">Group</th><th class="border-r border-[#c7d7db] px-3 py-2 text-right font-bold">Cash Sales</th><th class="border-r border-[#c7d7db] px-3 py-2 text-right font-bold">Card Sales</th><th class="border-r border-[#c7d7db] px-3 py-2 text-right font-bold">Other Sales</th><th class="border-r border-[#c7d7db] px-3 py-2 text-right font-bold">Transaction</th><th class="px-3 py-2 text-right font-bold">Total</th></tr></thead>
                    <tbody id="reportRows" class="text-[#5f747b]"></tbody>
                    <tfoot id="reportFooter" class="border-t border-[#aebfc5] bg-[#f0f6f7] font-bold text-[#456772]"></tfoot>
            </x-tables.legacy-table-shell>
        </div>
    </div>
</section>
