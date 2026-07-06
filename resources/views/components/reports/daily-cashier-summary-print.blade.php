@props([
    'id' => 'dailyCashierSummaryPrintModal',
    'mode' => 'modal',
])

@php
    $isModal = $mode === 'modal';
    $rootId = $isModal ? $id . 'Root' : $id . 'Page';
    $viewportId = $id . 'Viewport';
    $canvasId = $id . 'Canvas';
    $paperId = $id . 'Paper';
    $rowsId = $id . 'Rows';
    $footerId = $id . 'Footer';
    $zoomInfoId = $id . 'ZoomInfo';
    $sessionKey = 'ibis-hotel:cashier-session:' . session('cashier_login.logged_in_at', now()->toIso8601String()) . ':' . session('cashier_login.cashier', session('cashier_login.display_name', 'ADHA'));
    $previewStorageKey = $sessionKey . ':daily-cashier-summary-preview';

    $initialRows = [];

    $formatCurrency = fn ($value) => 'Rp ' . number_format((float) $value, 0, ',', '.');
    $initialTotals = [
        'groupCount' => count($initialRows),
        'cash' => collect($initialRows)->sum('cash'),
        'card' => collect($initialRows)->sum('card'),
        'other' => collect($initialRows)->sum('other'),
        'transaction' => collect($initialRows)->sum('transaction'),
    ];
    $initialTotals['totalSales'] = $initialTotals['cash'] + $initialTotals['card'] + $initialTotals['other'];

    $initialPayload = [
        'reportDate' => now()->format('d-M-Y'),
        'reportDateLong' => now()->locale('id')->translatedFormat('d F Y'),
        'groupBy' => 'Cashier',
        'selection' => 'Belum ada preview',
        'metaLine' => 'Date: ' . now()->locale('id')->translatedFormat('d F Y') . ' · Group By: Cashier',
        'printedAt' => now()->format('d-M-Y H:i'),
        'printedBy' => session('cashier_login.display_name', 'ADHA'),
        'rows' => $initialRows,
        'totals' => $initialTotals,
        'salesOnly' => false,
    ];
@endphp

<style>
    @if ($isModal)
        #{{ $id }}::backdrop {
            background: rgba(15, 23, 42, .45);
            backdrop-filter: blur(2px);
        }
    @endif

    #{{ $rootId }} [data-toolbar-button] {
        display: grid;
        height: 25px;
        width: 27px;
        place-items: center;
        border: 1px solid transparent;
        color: #59737d;
        transition: .15s ease;
    }

    #{{ $rootId }} [data-toolbar-button]:hover {
        border-color: #89aab4;
        background: #f5fafb;
        color: #26718c;
    }

    @media print {
        body * {
            visibility: hidden;
        }

        #{{ $paperId }},
        #{{ $paperId }} * {
            visibility: visible;
        }

        #{{ $paperId }} {
            position: fixed;
            inset: 0;
            width: 100%;
            max-width: none;
            min-width: 0;
            padding: 24px;
            overflow: visible;
            box-shadow: none;
            transform: none !important;
            margin: 0 !important;
            background: white;
        }
    }
</style>

@if ($isModal)
    <dialog id="{{ $id }}" class="m-auto w-[calc(100%-1rem)] max-w-[1400px] overflow-hidden rounded-sm border border-[#8fa6ae] bg-[#edf4f6] p-0 shadow-2xl">
        <x-ui.window-titlebar title="Daily Cashier Summary Print" symbol="R">
            <x-slot:actions>
                <button type="button" data-close-daily-cashier-print class="grid h-5 w-5 place-items-center rounded-[2px] text-[#6b7f87] transition hover:bg-[#c9e0e7] hover:text-[#244e5f]" aria-label="Tutup preview">
                    ✕
                </button>
            </x-slot:actions>
        </x-ui.window-titlebar>
        <div id="{{ $rootId }}" class="min-w-0 bg-[linear-gradient(135deg,#c9d9de_0%,#eef5f6_52%,#cbd9dd_100%)]">
@else
    <section id="{{ $rootId }}" class="min-h-screen overflow-hidden bg-[linear-gradient(135deg,#c9d9de_0%,#eef5f6_52%,#cbd9dd_100%)]">
@endif
    <div class="flex h-10 items-center gap-1 border-b border-[#a8bcc2] bg-[#e6f0f2] px-3" data-print-hidden>
        <button type="button" data-toolbar-button data-daily-cashier-print title="Print">
            <i data-lucide="printer" class="h-3.5 w-3.5"></i>
        </button>

        <button type="button" data-toolbar-button data-daily-cashier-preview title="Reset Preview">
            <i data-lucide="scan-search" class="h-3.5 w-3.5"></i>
        </button>

        <button type="button" data-toolbar-button data-daily-cashier-zoom-out title="Zoom Out">
            <i data-lucide="zoom-out" class="h-3.5 w-3.5"></i>
        </button>

        <button type="button" data-toolbar-button data-daily-cashier-zoom-in title="Zoom In">
            <i data-lucide="zoom-in" class="h-3.5 w-3.5"></i>
        </button>

        <span id="{{ $zoomInfoId }}" class="ml-2 text-[10px] text-[#617982]">Zoom: 100%</span>

        <div class="ml-auto text-[10px] font-bold text-[#6b8087]">
            SAP CRYSTAL REPORTS
        </div>
    </div>

    <div class="{{ $isModal ? 'grid h-[min(820px,calc(100vh-7rem))] grid-cols-[220px_1fr]' : 'grid min-h-[calc(100vh-40px)] grid-cols-[220px_1fr]' }}">
        <aside class="border-r border-[#aabcc1] bg-[#eaf2f4]" data-print-hidden>
            <div class="border-b border-[#bdcdd2] px-3 py-2 text-[10px] font-bold text-[#607981]">
                <div class="flex items-center gap-1.5">
                    <i data-lucide="folder-open" class="h-3.5 w-3.5 text-[#41788c]"></i>
                    <span id="{{ $id }}TreeLabel">Cashier Summary ({{ $initialTotals['groupCount'] }})</span>
                </div>
            </div>

            <button type="button" class="flex w-full items-center gap-2 border-b border-[#d8e3e6] bg-[#dbeef3] px-4 py-2 text-left text-[10px] font-semibold text-[#356d82]">
                <i data-lucide="file-text" class="h-3.5 w-3.5"></i>
                <span id="{{ $id }}TreeItem">Cashier Report</span>
            </button>

            <div class="space-y-1 px-4 py-4 text-[10px] leading-relaxed text-[#627b83]">
                <p class="font-bold text-[#476470]">Selection</p>
                <p id="{{ $id }}SelectionSide">{{ $initialPayload['selection'] }}</p>

                <p class="pt-3 font-bold text-[#476470]">Printed By</p>
                <p id="{{ $id }}PrintedBySide">{{ $initialPayload['printedBy'] }}</p>
            </div>
        </aside>

        <main class="min-w-0 bg-[#d9e6e9] p-3">
            <div class="flex h-8 items-end" data-print-hidden>
                <div class="rounded-t-sm border border-b-0 border-[#afc1c6] bg-[#edf5f6] px-4 py-2 text-[10px] font-bold text-[#526d76]">
                    Main Report
                </div>
            </div>

            <section id="{{ $viewportId }}" class="{{ $isModal ? 'h-[calc(100%-2rem)]' : 'h-[calc(100vh-130px)]' }} overflow-auto border border-[#97afb6] bg-[#cddade] p-4 shadow-inner">
                <div class="flex min-h-full w-full min-w-max justify-center">
                    <div id="{{ $canvasId }}" class="flex-none">
                        <div id="{{ $paperId }}" class="w-[1100px] max-w-[1100px] bg-white px-6 py-7 text-[10px] text-[#4d626a] shadow-[0_2px_10px_rgba(15,23,42,.25)]">
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

                                <p id="{{ $id }}ReportMeta" class="mt-2 text-[11px] text-[#6c8087]">
                                    {{ $initialPayload['metaLine'] }}
                                </p>
                            </div>

                            <div class="pt-3 text-right text-[10px] leading-relaxed text-[#586f77]">
                                <p>Page 1 of 1 (PSR01200)</p>
                                <p>Print Date : <span id="{{ $id }}PrintedAt">{{ $initialPayload['printedAt'] }}</span></p>
                                <p>Printed By : <span id="{{ $id }}PrintedBy">{{ $initialPayload['printedBy'] }}</span></p>
                            </div>
                        </div>

                        <div class="mt-4 grid grid-cols-[110px_1fr] gap-x-2 gap-y-1 border-b border-[#8fa6ad] pb-3 text-[11px]">
                            <span class="font-bold">Trans. Date :</span>
                            <span id="{{ $id }}ReportDate">{{ $initialPayload['reportDate'] }}</span>

                            <span class="font-bold">Group By :</span>
                            <span id="{{ $id }}GroupBy">{{ $initialPayload['groupBy'] }}</span>

                            <span class="font-bold">Selection :</span>
                            <span id="{{ $id }}SelectionSummary">{{ $initialPayload['selection'] }}</span>
                        </div>

                        <div class="mt-4 grid grid-cols-3 gap-3 text-[11px]">
                            <div class="border border-[#b5c8ce] bg-[#f4f8f9] px-4 py-3">
                                <p class="text-[10px] font-bold uppercase tracking-wide text-[#5a7580]">Total Group</p>
                                <p id="{{ $id }}GroupCount" class="mt-2 text-lg font-black text-[#3b6675]">{{ $initialTotals['groupCount'] }}</p>
                            </div>

                            <div class="border border-[#b5c8ce] bg-[#f4f8f9] px-4 py-3">
                                <p class="text-[10px] font-bold uppercase tracking-wide text-[#5a7580]">Total Transaction</p>
                                <p id="{{ $id }}TransactionCount" class="mt-2 text-lg font-black text-[#3b6675]">{{ $initialTotals['transaction'] }}</p>
                            </div>

                            <div class="border border-[#b5c8ce] bg-[#f4f8f9] px-4 py-3">
                                <p class="text-[10px] font-bold uppercase tracking-wide text-[#5a7580]">Total Sales</p>
                                <p id="{{ $id }}TotalSales" class="mt-2 text-lg font-black text-[#3b6675]">{{ $formatCurrency($initialTotals['totalSales']) }}</p>
                            </div>
                        </div>

                        <div class="mt-4 overflow-x-auto">
                            <table class="w-full min-w-[960px] border-collapse text-[10px]">
                                <thead class="border-y border-[#7f989f] bg-[#eff5f6] font-bold text-[#526b74]">
                                    <tr>
                                        <th class="w-[220px] border-r border-[#b6c8cd] px-3 py-2 text-left">Group</th>
                                        <th class="w-[150px] border-r border-[#b6c8cd] px-3 py-2 text-right">Cash Sales</th>
                                        <th class="w-[150px] border-r border-[#b6c8cd] px-3 py-2 text-right">Card Sales</th>
                                        <th class="w-[150px] border-r border-[#b6c8cd] px-3 py-2 text-right">Other Sales</th>
                                        <th class="w-[120px] border-r border-[#b6c8cd] px-3 py-2 text-right">Transaction</th>
                                        <th class="w-[170px] px-3 py-2 text-right">Total</th>
                                    </tr>
                                </thead>

                                <tbody id="{{ $rowsId }}">
                                    @foreach ($initialRows as $row)
                                        <tr class="border-b border-[#c3d2d6]">
                                            <td class="border-r border-[#d5e0e3] px-3 py-2 font-semibold">{{ $row['group'] }}</td>
                                            <td class="border-r border-[#d5e0e3] px-3 py-2 text-right">{{ $formatCurrency($row['cash']) }}</td>
                                            <td class="border-r border-[#d5e0e3] px-3 py-2 text-right">{{ $formatCurrency($row['card']) }}</td>
                                            <td class="border-r border-[#d5e0e3] px-3 py-2 text-right">{{ $formatCurrency($row['other']) }}</td>
                                            <td class="border-r border-[#d5e0e3] px-3 py-2 text-right">{{ $row['transaction'] }}</td>
                                            <td class="px-3 py-2 text-right font-bold">{{ $formatCurrency($row['cash'] + $row['card'] + $row['other']) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>

                                <tfoot id="{{ $footerId }}" class="border-y border-[#7e989f] bg-[#f3f8f9] font-bold text-[#425f69]">
                                    <tr>
                                        <td class="border-r border-[#c8d7db] px-3 py-2">TOTAL</td>
                                        <td class="border-r border-[#c8d7db] px-3 py-2 text-right">{{ $formatCurrency($initialTotals['cash']) }}</td>
                                        <td class="border-r border-[#c8d7db] px-3 py-2 text-right">{{ $formatCurrency($initialTotals['card']) }}</td>
                                        <td class="border-r border-[#c8d7db] px-3 py-2 text-right">{{ $formatCurrency($initialTotals['other']) }}</td>
                                        <td class="border-r border-[#c8d7db] px-3 py-2 text-right">{{ $initialTotals['transaction'] }}</td>
                                        <td class="px-3 py-2 text-right">{{ $formatCurrency($initialTotals['totalSales']) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>
@if ($isModal)
        </div>
    </dialog>
@else
    </section>
@endif

<script>
    (() => {
        const root = document.getElementById(@js($rootId));

        if (!root || root.dataset.ready === 'true') {
            return;
        }

        root.dataset.ready = 'true';

        const isModal = {{ $isModal ? 'true' : 'false' }};
        const modal = isModal ? document.getElementById(@js($id)) : null;
        const viewport = document.getElementById(@js($viewportId));
        const canvas = document.getElementById(@js($canvasId));
        const paper = document.getElementById(@js($paperId));
        const rowsBody = document.getElementById(@js($rowsId));
        const footer = document.getElementById(@js($footerId));
        const zoomInfo = document.getElementById(@js($zoomInfoId));
        const reportMeta = document.getElementById(@js($id . 'ReportMeta'));
        const printedAt = document.getElementById(@js($id . 'PrintedAt'));
        const printedBy = document.getElementById(@js($id . 'PrintedBy'));
        const printedBySide = document.getElementById(@js($id . 'PrintedBySide'));
        const reportDate = document.getElementById(@js($id . 'ReportDate'));
        const groupByLabel = document.getElementById(@js($id . 'GroupBy'));
        const selectionSummary = document.getElementById(@js($id . 'SelectionSummary'));
        const selectionSide = document.getElementById(@js($id . 'SelectionSide'));
        const treeLabel = document.getElementById(@js($id . 'TreeLabel'));
        const treeItem = document.getElementById(@js($id . 'TreeItem'));
        const groupCount = document.getElementById(@js($id . 'GroupCount'));
        const transactionCount = document.getElementById(@js($id . 'TransactionCount'));
        const totalSales = document.getElementById(@js($id . 'TotalSales'));
        const initialPayload = @json($initialPayload);
        const previewStorageKey = @json($previewStorageKey);

        const currencyFormatter = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            maximumFractionDigits: 0,
        });
        const numberFormatter = new Intl.NumberFormat('id-ID');
        const escapeHtml = (value = '') => String(value).replace(/[&<>"']/g, (char) => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;',
        }[char]));
        const sum = (rows, key) => rows.reduce((total, row) => total + Number(row[key] ?? 0), 0);

        let zoomLevel = 1;

        function loadStoredPreview() {
            try {
                const parsed = JSON.parse(localStorage.getItem(previewStorageKey) ?? 'null');
                return parsed && typeof parsed === 'object' ? parsed : null;
            } catch (error) {
                return null;
            }
        }

        function storePreview(payload = {}) {
            try {
                const snapshot = { ...payload };
                delete snapshot.autoPrint;
                localStorage.setItem(previewStorageKey, JSON.stringify(snapshot));
            } catch (error) {
                // Ignore storage write failures in preview mode.
            }
        }

        function updateZoom() {
            if (!paper || !canvas || !zoomInfo) {
                return;
            }

            paper.style.transformOrigin = 'top left';
            paper.style.transform = `scale(${zoomLevel})`;
            canvas.style.width = `${paper.offsetWidth * zoomLevel}px`;
            canvas.style.height = `${paper.offsetHeight * zoomLevel}px`;
            zoomInfo.textContent = `Zoom: ${Math.round(zoomLevel * 100)}%`;
        }

        function normalizeRows(rows) {
            if (!Array.isArray(rows)) {
                return [];
            }

            return rows.map((row) => ({
                group: row.group ?? '-',
                cash: Number(row.cash ?? 0),
                card: Number(row.card ?? row.cardSales ?? 0),
                other: Number(row.other ?? row.otherSales ?? 0),
                transaction: Number(row.transaction ?? 0),
            }));
        }

        function normalizePayload(payload = {}) {
            const rows = normalizeRows(payload.rows ?? initialPayload.rows);
            const totals = payload.totals ?? {};
            const totalCash = Number(totals.cash ?? sum(rows, 'cash'));
            const totalCard = Number(totals.card ?? sum(rows, 'card'));
            const totalOther = Number(totals.other ?? sum(rows, 'other'));
            const totalTransaction = Number(totals.transaction ?? sum(rows, 'transaction'));
            const totalSummarySales = Number(totals.totalSales ?? (totalCash + totalCard + totalOther));

            return {
                reportDate: payload.reportDate ?? initialPayload.reportDate,
                reportDateLong: payload.reportDateLong ?? payload.reportDate ?? initialPayload.reportDateLong,
                groupBy: payload.groupBy ?? initialPayload.groupBy,
                selection: payload.selection ?? initialPayload.selection,
                metaLine: payload.metaLine ?? initialPayload.metaLine,
                printedAt: payload.printedAt ?? initialPayload.printedAt,
                printedBy: payload.printedBy ?? initialPayload.printedBy,
                salesOnly: Boolean(payload.salesOnly ?? initialPayload.salesOnly),
                autoPrint: Boolean(payload.autoPrint),
                rows,
                totals: {
                    groupCount: Number(totals.groupCount ?? rows.length),
                    cash: totalCash,
                    card: totalCard,
                    other: totalOther,
                    transaction: totalTransaction,
                    totalSales: totalSummarySales,
                },
            };
        }

        function renderTable(data) {
            if (!rowsBody || !footer) {
                return;
            }

            if (!data.rows.length) {
                rowsBody.innerHTML = '<tr class="border-b border-[#c3d2d6]"><td colspan="6" class="px-3 py-8 text-center text-[11px] text-[#6a7d84]">Tidak ada data untuk filter yang dipilih.</td></tr>';
            } else {
                rowsBody.innerHTML = data.rows.map((row) => {
                    const total = row.cash + row.card + row.other;

                    return `
                        <tr class="border-b border-[#c3d2d6]">
                            <td class="border-r border-[#d5e0e3] px-3 py-2 font-semibold">${escapeHtml(row.group)}</td>
                            <td class="border-r border-[#d5e0e3] px-3 py-2 text-right">${currencyFormatter.format(row.cash)}</td>
                            <td class="border-r border-[#d5e0e3] px-3 py-2 text-right">${currencyFormatter.format(row.card)}</td>
                            <td class="border-r border-[#d5e0e3] px-3 py-2 text-right">${currencyFormatter.format(row.other)}</td>
                            <td class="border-r border-[#d5e0e3] px-3 py-2 text-right">${numberFormatter.format(row.transaction)}</td>
                            <td class="px-3 py-2 text-right font-bold">${currencyFormatter.format(total)}</td>
                        </tr>
                    `;
                }).join('');
            }

            footer.innerHTML = `
                <tr>
                    <td class="border-r border-[#c8d7db] px-3 py-2">TOTAL</td>
                    <td class="border-r border-[#c8d7db] px-3 py-2 text-right">${currencyFormatter.format(data.totals.cash)}</td>
                    <td class="border-r border-[#c8d7db] px-3 py-2 text-right">${currencyFormatter.format(data.totals.card)}</td>
                    <td class="border-r border-[#c8d7db] px-3 py-2 text-right">${currencyFormatter.format(data.totals.other)}</td>
                    <td class="border-r border-[#c8d7db] px-3 py-2 text-right">${numberFormatter.format(data.totals.transaction)}</td>
                    <td class="px-3 py-2 text-right">${currencyFormatter.format(data.totals.totalSales)}</td>
                </tr>
            `;
        }

        function populate(payload = {}) {
            const data = normalizePayload(payload);

            reportMeta.textContent = data.metaLine;
            reportDate.textContent = data.reportDate;
            groupByLabel.textContent = data.salesOnly ? `${data.groupBy} · Sales Only` : data.groupBy;
            selectionSummary.textContent = data.selection;
            selectionSide.textContent = data.selection;
            printedAt.textContent = data.printedAt;
            printedBy.textContent = data.printedBy;
            printedBySide.textContent = data.printedBy;
            treeLabel.textContent = `${data.groupBy} Summary (${numberFormatter.format(data.totals.groupCount)})`;
            treeItem.textContent = `${data.groupBy} Report`;
            groupCount.textContent = numberFormatter.format(data.totals.groupCount);
            transactionCount.textContent = numberFormatter.format(data.totals.transaction);
            totalSales.textContent = currencyFormatter.format(data.totals.totalSales);

            renderTable(data);



            return data;
        }

        function openPreview(payload = {}) {
            const data = populate(payload);

            if (isModal && modal && typeof modal.showModal === 'function' && !modal.open) {
                modal.showModal();
            }

            if (data.autoPrint) {
                window.setTimeout(() => window.print(), 180);
            }
        }

        (modal ?? root).querySelectorAll('[data-close-daily-cashier-print]').forEach((button) => {
            button.addEventListener('click', () => modal?.close());
        });

        root.querySelector('[data-daily-cashier-print]')?.addEventListener('click', () => {
            window.print();
        });

        root.querySelector('[data-daily-cashier-preview]')?.addEventListener('click', () => {
            zoomLevel = 1;
            updateZoom();
            viewport?.scrollTo({ top: 0, left: 0, behavior: 'smooth' });
        });

        root.querySelector('[data-daily-cashier-zoom-in]')?.addEventListener('click', () => {
            if (zoomLevel < 1.8) {
                zoomLevel = Number((zoomLevel + 0.1).toFixed(2));
                updateZoom();
            }
        });

        root.querySelector('[data-daily-cashier-zoom-out]')?.addEventListener('click', () => {
            if (zoomLevel > 0.7) {
                zoomLevel = Number((zoomLevel - 0.1).toFixed(2));
                updateZoom();
            }
        });

        modal?.addEventListener('click', (event) => {
            if (event.target === modal) {
                modal.close();
            }
        });

        window.addEventListener('daily-cashier-summary:print-preview', (event) => {
            storePreview(event.detail || {});
            openPreview(event.detail || {});
        });

        populate(loadStoredPreview() ?? initialPayload);
    })();
</script>
