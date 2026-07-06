@props([
    'outlets' => [],
    'cashiers' => [],
])

@push('scripts')
<script>
    (() => {
        const outletData = @json($outlets);
        const cashierData = @json($cashiers);
        const printedBy = @json(session('cashier_login.display_name', 'ADHA'));
        const cashierCode = @json(session('cashier_login.cashier', session('cashier_login.display_name', 'ADHA')));
        const sessionStartedAt = @json(session('cashier_login.logged_in_at', now()->toIso8601String()));
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
        const cashierGroupInput = document.querySelector('input[name="groupBy"][value="cashier"]');
        const cashierLookupButtons = [...document.querySelectorAll('[data-cashier-target]')];
        const outputInputs = [...document.querySelectorAll('input[name="outputTo"]')];
        const printerName = document.getElementById('printerName');
        const printerPropertiesButton = document.getElementById('printerPropertiesButton');
        const printerPropertiesModal = document.getElementById('printerPropertiesModal');
        const printSummaryButton = document.getElementById('printSummaryButton');
        const emptyReportState = document.getElementById('emptyReportState');
        const reportContainer = document.getElementById('reportContainer');
        const reportRows = document.getElementById('reportRows');
        const reportFooter = document.getElementById('reportFooter');
        const browserPrintButton = document.getElementById('browserPrintButton');
        const sessionStorageKey = `ibis-hotel:cashier-session:${sessionStartedAt}:${cashierCode}`;
        const transactionsStorageKey = `${sessionStorageKey}:transactions`;
        const previewStorageKey = `${sessionStorageKey}:daily-cashier-summary-preview`;
        const outletNameToCode = Object.fromEntries(outletData.map((item) => [item.name, item.code]));
        const outletCodeToName = Object.fromEntries(outletData.map((item) => [item.code, item.name]));
        const outletIndexMap = Object.fromEntries(outletData.map((item, index) => [item.code, index]));
        const cashierIndexMap = Object.fromEntries(cashierData.map((item, index) => [item.code, index]));

        if (!summaryDate || !printSummaryButton || !reportRows) return;

        const rupiah = (value) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(value);
        const findName = (data, code) => data.find((item) => item.code === code)?.name ?? '-';
        const selectedGroupBy = () => groupByInputs.find((input) => input.checked)?.value || 'outlet';
        const parseInputDate = (value) => {
            const [year, month, day] = value.split('-').map(Number);
            return new Date(year, month - 1, day);
        };
        const shortDate = (date) => new Intl.DateTimeFormat('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }).format(date).replace(/ /g, '-');
        const longDate = (date) => new Intl.DateTimeFormat('id-ID', { day: '2-digit', month: 'long', year: 'numeric' }).format(date);
        const timeOnly = (date) => new Intl.DateTimeFormat('en-GB', { hour: '2-digit', minute: '2-digit', hour12: false }).format(date);
        let cashierLookupTarget = 'from';
        let lastReportPayload = null;

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
        function loadTransactions() {
            try {
                const parsed = JSON.parse(localStorage.getItem(transactionsStorageKey) ?? '[]');
                return Array.isArray(parsed) ? parsed : [];
            } catch (error) {
                return [];
            }
        }
        function storeLastPreview(payload) {
            try {
                localStorage.setItem(previewStorageKey, JSON.stringify(payload));
            } catch (error) {
                // Ignore storage write failures in preview mode.
            }
        }
        function buildPaymentBreakdown(transaction) {
            if (transaction.paymentBreakdown && typeof transaction.paymentBreakdown === 'object') {
                return {
                    cash: Number(transaction.paymentBreakdown.cash ?? 0),
                    card: Number(transaction.paymentBreakdown.card ?? 0),
                    other: Number(transaction.paymentBreakdown.other ?? 0),
                };
            }

            return (Array.isArray(transaction.payments) ? transaction.payments : []).reduce((totals, payment) => {
                const label = String(payment.label ?? payment.paymentType ?? '').toUpperCase();
                const amount = Number(payment.amount ?? 0);

                if (label.includes('CASH')) {
                    totals.cash += amount;
                } else if (
                    label.includes('CARD')
                    || label.includes('QRIS')
                    || label.includes('VISA')
                    || label.includes('MASTER')
                    || label.includes('DEBIT')
                ) {
                    totals.card += amount;
                } else {
                    totals.other += amount;
                }

                return totals;
            }, { cash: 0, card: 0, other: 0 });
        }
        function withinRange(code, fromCode, toCode, indexMap) {
            const currentIndex = indexMap[code];
            const fromIndex = indexMap[fromCode];
            const toIndex = indexMap[toCode];

            if (currentIndex === undefined || fromIndex === undefined || toIndex === undefined) {
                return true;
            }

            const lower = Math.min(fromIndex, toIndex);
            const upper = Math.max(fromIndex, toIndex);

            return currentIndex >= lower && currentIndex <= upper;
        }
        function resolveTransactions() {
            const dateValue = summaryDate.value;
            const groupBy = selectedGroupBy();
            const groups = new Map();
            const filteredTransactions = loadTransactions().filter((transaction) => {
                const businessDate = transaction.businessDate ?? transaction.transactionAt?.slice(0, 10);
                const outletCode = transaction.outletCode ?? outletNameToCode[transaction.outletName] ?? outletFrom.value;
                const cashierCodeValue = transaction.cashierCode ?? cashierFrom.value;

                if (businessDate !== dateValue) {
                    return false;
                }

                if (groupBy === 'outlet') {
                    return withinRange(outletCode, outletFrom.value, outletTo.value, outletIndexMap);
                }

                return withinRange(cashierCodeValue, cashierFrom.value, cashierTo.value, cashierIndexMap);
            });

            filteredTransactions.forEach((transaction) => {
                const outletCode = transaction.outletCode ?? outletNameToCode[transaction.outletName] ?? outletFrom.value;
                const cashierCodeValue = transaction.cashierCode ?? cashierFrom.value;
                const breakdown = buildPaymentBreakdown(transaction);
                const subTotal = Number(transaction.subTotal ?? 0);
                const discount = Number(transaction.discount ?? 0);
                const grossTotal = Number(transaction.total ?? (breakdown.cash + breakdown.card + breakdown.other));
                const salesBase = Math.max(0, subTotal - discount);
                const ratio = salesOnly.checked && grossTotal > 0
                    ? Math.min(1, salesBase / grossTotal)
                    : 1;
                const groupCode = groupBy === 'outlet' ? outletCode : cashierCodeValue;
                const groupLabel = groupBy === 'outlet'
                    ? (transaction.outletName ?? outletCodeToName[outletCode] ?? '-')
                    : (transaction.cashierCode ?? transaction.cashierName ?? '-');
                const row = groups.get(groupCode) ?? {
                    code: groupCode,
                    group: groupBy === 'outlet'
                        ? (transaction.outletName ?? outletCodeToName[outletCode] ?? '-')
                        : (transaction.cashierCode ?? transaction.cashierName ?? '-'),
                    cash: 0,
                    card: 0,
                    other: 0,
                    transaction: 0,
                };

                row.cash += breakdown.cash * ratio;
                row.card += breakdown.card * ratio;
                row.other += breakdown.other * ratio;
                row.transaction += 1;
                row.group = groupLabel;

                groups.set(groupCode, row);
            });

            const indexMap = groupBy === 'outlet' ? outletIndexMap : cashierIndexMap;

            return [...groups.values()]
                .sort((left, right) => {
                    const leftIndex = indexMap[left.code] ?? Number.MAX_SAFE_INTEGER;
                    const rightIndex = indexMap[right.code] ?? Number.MAX_SAFE_INTEGER;
                    return leftIndex - rightIndex;
                })
                .map((row) => ({
                    group: row.group,
                    cash: Math.round(row.cash),
                    card: Math.round(row.card),
                    other: Math.round(row.other),
                    transaction: row.transaction,
                }));
        }
        function openPrintPreview(autoPrint = false) {
            if (!lastReportPayload) return;

            storeLastPreview(lastReportPayload);

            window.dispatchEvent(new CustomEvent('daily-cashier-summary:print-preview', {
                detail: {
                    ...lastReportPayload,
                    autoPrint,
                },
            }));
        }
        function renderReport({ openPreview = false, autoPrint = false } = {}) {
            const rows = resolveTransactions();
            const groupBy = selectedGroupBy();
            const selectedDate = parseInputDate(summaryDate.value);
            const dateText = longDate(selectedDate);
            const totalCash = rows.reduce((sum,row) => sum + row.cash, 0);
            const totalCard = rows.reduce((sum,row) => sum + row.card, 0);
            const totalOther = rows.reduce((sum,row) => sum + row.other, 0);
            const totalTransaction = rows.reduce((sum,row) => sum + row.transaction, 0);
            const totalSales = totalCash + totalCard + totalOther;
            const groupByLabel = groupBy === 'outlet' ? 'Outlet' : 'Cashier';
            const selection = groupBy === 'outlet'
                ? `${outletFromName.value} - ${outletToName.value}`
                : `${cashierFromName.value} - ${cashierToName.value}`;
            const printedAt = `${shortDate(new Date())} ${timeOnly(new Date())}`;

            document.getElementById('reportMeta').textContent = `Date: ${dateText} · Group By: ${groupByLabel}${salesOnly.checked ? ' · Sales Only' : ''}`;
            document.getElementById('reportCashierCount').textContent = rows.length;
            document.getElementById('reportTransactionCount').textContent = totalTransaction;
            document.getElementById('reportTotalSales').textContent = rupiah(totalSales);
            reportRows.innerHTML = rows.length > 0
                ? rows.map((row) => {
                    const total = row.cash + row.card + row.other;
                    return `<tr class="border-b border-[#d7e3e6] hover:bg-[#f6fbfc]"><td class="border-r border-[#e0e9eb] px-3 py-2 font-semibold">${row.group}</td><td class="border-r border-[#e0e9eb] px-3 py-2 text-right">${rupiah(row.cash)}</td><td class="border-r border-[#e0e9eb] px-3 py-2 text-right">${rupiah(row.card)}</td><td class="border-r border-[#e0e9eb] px-3 py-2 text-right">${rupiah(row.other)}</td><td class="border-r border-[#e0e9eb] px-3 py-2 text-right">${row.transaction}</td><td class="px-3 py-2 text-right font-bold text-[#3c6b7b]">${rupiah(total)}</td></tr>`;
                }).join('')
                : '<tr><td colspan="6" class="px-3 py-8 text-center text-[#7d9097]">Tidak ada transaksi sesi untuk filter yang dipilih.</td></tr>';
            reportFooter.innerHTML = `<tr><td class="border-r border-[#c7d7db] px-3 py-2">TOTAL</td><td class="border-r border-[#c7d7db] px-3 py-2 text-right">${rupiah(totalCash)}</td><td class="border-r border-[#c7d7db] px-3 py-2 text-right">${rupiah(totalCard)}</td><td class="border-r border-[#c7d7db] px-3 py-2 text-right">${rupiah(totalOther)}</td><td class="border-r border-[#c7d7db] px-3 py-2 text-right">${totalTransaction}</td><td class="px-3 py-2 text-right">${rupiah(totalSales)}</td></tr>`;
            emptyReportState.classList.add('hidden');
            reportContainer.classList.remove('hidden');

            lastReportPayload = {
                reportDate: shortDate(selectedDate),
                reportDateLong: dateText,
                groupBy: groupByLabel,
                selection,
                metaLine: `Date: ${dateText} · Group By: ${groupByLabel}${salesOnly.checked ? ' · Sales Only' : ''}`,
                printedAt,
                printedBy,
                salesOnly: salesOnly.checked,
                rows,
                totals: {
                    groupCount: rows.length,
                    cash: totalCash,
                    card: totalCard,
                    other: totalOther,
                    transaction: totalTransaction,
                    totalSales,
                },
            };

            storeLastPreview(lastReportPayload);

            if (openPreview) {
                openPrintPreview(autoPrint);
            }
        }

        groupByInputs.forEach((input) => input.addEventListener('change', updateGroupByUI));
        outputInputs.forEach((input) => input.addEventListener('change', updateOutputUI));
        cashierLookupButtons.forEach((button) => {
            button.addEventListener('click', () => {
                cashierLookupTarget = button.dataset.cashierTarget || 'from';
            });
        });
        outletFrom.addEventListener('change', updateOutletNames);
        outletTo.addEventListener('change', updateOutletNames);
        cashierFrom.addEventListener('change', updateCashierNames);
        cashierTo.addEventListener('change', updateCashierNames);
        printSummaryButton.addEventListener('click', () => {
            renderReport({
                openPreview: true,
                autoPrint: outputInputs.find((input) => input.checked)?.value === 'printer',
            });
        });
        browserPrintButton?.addEventListener('click', () => {
            if (!lastReportPayload) {
                renderReport({ openPreview: true });
                return;
            }

            openPrintPreview(false);
        });
        document.getElementById('closePrinterProperties').addEventListener('click', () => printerPropertiesModal.close());
        document.getElementById('closePrinterPropertiesTop')?.addEventListener('click', () => printerPropertiesModal.close());
        document.getElementById('savePrinterProperties').addEventListener('click', () => printerPropertiesModal.close());
        printerPropertiesModal.addEventListener('click', (event) => { if (event.target === printerPropertiesModal) printerPropertiesModal.close(); });
        window.addEventListener('cashier-selected', (event) => {
            const { cashierId, name } = event.detail || {};
            if (!cashierId) return;

            if (cashierGroupInput) {
                cashierGroupInput.checked = true;
                updateGroupByUI();
            }

            const targetField = cashierLookupTarget === 'to' ? cashierTo : cashierFrom;
            const targetNameField = cashierLookupTarget === 'to' ? cashierToName : cashierFromName;

            targetField.value = cashierId;
            targetNameField.value = name ?? findName(cashierData, cashierId);
            updateCashierNames();
        });
        window.addEventListener('storage', (event) => {
            if (event.key !== transactionsStorageKey || reportContainer.classList.contains('hidden')) {
                return;
            }

            renderReport();
        });

        updateOutletNames(); updateCashierNames(); updateGroupByUI(); updateOutputUI();
    })();
</script>
@endpush
