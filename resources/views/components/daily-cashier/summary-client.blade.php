@props([
    'outlets' => [],
    'cashiers' => [],
])

@push('scripts')
<script>
    (() => {
        const outletData = @json($outlets);
        const cashierData = @json($cashiers);
        const summaryDate = document.getElementById('summaryDate');
        const groupByInputs = [...document.querySelectorAll('input[name="groupBy"]')];
        const salesOnly = document.getElementById('salesOnly');
        const outletFieldset = document.getElementById('outletFieldset');
        const cashierFieldset = document.getElementById('cashierFieldset');
        const outletFrom = document.getElementById('outletFrom');
        const outletTo = document.getElementById('outletTo');
        const outletFromName = document.getElementById('outletFromName');
        const outletToName = document.getElementById('outletToName');
        const cashierFrom = document.getElementById('cashierFrom');
        const cashierTo = document.getElementById('cashierTo');
        const cashierFromName = document.getElementById('cashierFromName');
        const cashierToName = document.getElementById('cashierToName');
        const outputInputs = [...document.querySelectorAll('input[name="outputTo"]')];
        const printerName = document.getElementById('printerName');
        const printerPropertiesButton = document.getElementById('printerPropertiesButton');
        const printerPropertiesModal = document.getElementById('printerPropertiesModal');
        const printSummaryButton = document.getElementById('printSummaryButton');
        const emptyReportState = document.getElementById('emptyReportState');
        const reportContainer = document.getElementById('reportContainer');
        const reportRows = document.getElementById('reportRows');
        const reportFooter = document.getElementById('reportFooter');

        if (!summaryDate || !printSummaryButton || !reportRows) return;

        const rupiah = (value) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(value);
        const findName = (data, code) => data.find((item) => item.code === code)?.name ?? '-';
        const selectedGroupBy = () => groupByInputs.find((input) => input.checked)?.value || 'outlet';

        function updateOutletNames() {
            outletFromName.value = findName(outletData, outletFrom.value);
            outletToName.value = findName(outletData, outletTo.value);
        }
        function updateCashierNames() {
            cashierFromName.value = findName(cashierData, cashierFrom.value);
            cashierToName.value = findName(cashierData, cashierTo.value);
        }
        function updateGroupByUI() {
            const isOutlet = selectedGroupBy() === 'outlet';
            outletFieldset.classList.toggle('opacity-60', !isOutlet);
            cashierFieldset.classList.toggle('opacity-60', isOutlet);
            outletFrom.disabled = !isOutlet;
            outletTo.disabled = !isOutlet;
            cashierFrom.disabled = isOutlet;
            cashierTo.disabled = isOutlet;
        }
        function updateOutputUI() {
            const isPrinter = outputInputs.find((input) => input.checked)?.value === 'printer';
            printerName.disabled = !isPrinter;
            printerPropertiesButton.disabled = !isPrinter;
        }
        function sourceRows() {
            const factor = salesOnly.checked ? 0.78 : 1;
            const rows = selectedGroupBy() === 'outlet'
                ? [
                    { group:'Ibis Kitchen', cash:1285000, card:980000, other:127000, transaction:81 },
                    { group:'Restaurant Terrace', cash:710000, card:465000, other:52000, transaction:47 },
                    { group:'Room Service', cash:430000, card:295000, other:32000, transaction:31 },
                    { group:'Banquet', cash:935000, card:750000, other:88000, transaction:26 },
                    { group:'Pool Bar', cash:285000, card:210000, other:24000, transaction:19 },
                ]
                : [
                    { group:'ADHA', cash:1180000, card:935000, other:104000, transaction:72 },
                    { group:'FARHAN', cash:895000, card:735000, other:71000, transaction:55 },
                    { group:'NURUL', cash:860000, card:650000, other:82000, transaction:47 },
                    { group:'RIZKY', cash:710000, card:380000, other:66000, transaction:30 },
                ];
            return rows.map((row) => ({ ...row, cash:Math.round(row.cash * factor), card:Math.round(row.card * factor), other:Math.round(row.other * factor), transaction:Math.round(row.transaction * factor) }));
        }
        function renderReport() {
            const rows = sourceRows();
            const groupBy = selectedGroupBy();
            const dateText = new Intl.DateTimeFormat('id-ID', { day:'2-digit', month:'long', year:'numeric' }).format(new Date(`${summaryDate.value}T00:00:00`));
            const totalCash = rows.reduce((sum,row) => sum + row.cash, 0);
            const totalCard = rows.reduce((sum,row) => sum + row.card, 0);
            const totalOther = rows.reduce((sum,row) => sum + row.other, 0);
            const totalTransaction = rows.reduce((sum,row) => sum + row.transaction, 0);
            const totalSales = totalCash + totalCard + totalOther;

            document.getElementById('reportMeta').textContent = `Date: ${dateText} · Group By: ${groupBy === 'outlet' ? 'Outlet' : 'Cashier'}${salesOnly.checked ? ' · Sales Only' : ''}`;
            document.getElementById('reportCashierCount').textContent = rows.length;
            document.getElementById('reportTransactionCount').textContent = totalTransaction;
            document.getElementById('reportTotalSales').textContent = rupiah(totalSales);
            reportRows.innerHTML = rows.map((row) => {
                const total = row.cash + row.card + row.other;
                return `<tr class="border-b border-[#d7e3e6] hover:bg-[#f6fbfc]"><td class="border-r border-[#e0e9eb] px-3 py-2 font-semibold">${row.group}</td><td class="border-r border-[#e0e9eb] px-3 py-2 text-right">${rupiah(row.cash)}</td><td class="border-r border-[#e0e9eb] px-3 py-2 text-right">${rupiah(row.card)}</td><td class="border-r border-[#e0e9eb] px-3 py-2 text-right">${rupiah(row.other)}</td><td class="border-r border-[#e0e9eb] px-3 py-2 text-right">${row.transaction}</td><td class="px-3 py-2 text-right font-bold text-[#3c6b7b]">${rupiah(total)}</td></tr>`;
            }).join('');
            reportFooter.innerHTML = `<tr><td class="border-r border-[#c7d7db] px-3 py-2">TOTAL</td><td class="border-r border-[#c7d7db] px-3 py-2 text-right">${rupiah(totalCash)}</td><td class="border-r border-[#c7d7db] px-3 py-2 text-right">${rupiah(totalCard)}</td><td class="border-r border-[#c7d7db] px-3 py-2 text-right">${rupiah(totalOther)}</td><td class="border-r border-[#c7d7db] px-3 py-2 text-right">${totalTransaction}</td><td class="px-3 py-2 text-right">${rupiah(totalSales)}</td></tr>`;
            emptyReportState.classList.add('hidden');
            reportContainer.classList.remove('hidden');
            if (outputInputs.find((input) => input.checked)?.value === 'printer') setTimeout(() => window.print(), 250);
        }

        groupByInputs.forEach((input) => input.addEventListener('change', updateGroupByUI));
        outputInputs.forEach((input) => input.addEventListener('change', updateOutputUI));
        outletFrom.addEventListener('change', updateOutletNames);
        outletTo.addEventListener('change', updateOutletNames);
        cashierFrom.addEventListener('change', updateCashierNames);
        cashierTo.addEventListener('change', updateCashierNames);
        printSummaryButton.addEventListener('click', renderReport);
        document.getElementById('browserPrintButton').addEventListener('click', () => window.print());
        printerPropertiesButton.addEventListener('click', () => printerPropertiesModal.showModal());
        document.getElementById('closePrinterProperties').addEventListener('click', () => printerPropertiesModal.close());
        document.getElementById('closePrinterPropertiesTop')?.addEventListener('click', () => printerPropertiesModal.close());
        document.getElementById('savePrinterProperties').addEventListener('click', () => printerPropertiesModal.close());
        printerPropertiesModal.addEventListener('click', (event) => { if (event.target === printerPropertiesModal) printerPropertiesModal.close(); });

        updateOutletNames(); updateCashierNames(); updateGroupByUI(); updateOutputUI();
    })();
</script>
@endpush
