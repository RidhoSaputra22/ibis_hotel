@props([
    'id' => 'moveSplitModal',
    'tableNo' => '01',
    'orderNo' => '00000017',
    'items' => [
        [
            'seq' => '000100',
            'service_code' => 'FNB-4002',
            'description' => 'Chicken Teriyaki Cup',
            'seat_no' => '1',
            'qty' => 1,
            'split' => 'A',
            'split_qty' => 1,
        ],
        [
            'seq' => '000200',
            'service_code' => 'FNB-4008',
            'description' => 'Peach Strawberry',
            'seat_no' => '1',
            'qty' => 1,
            'split' => 'A',
            'split_qty' => 1,
        ],
    ],
])

@php
    $normalizedInitialItems = array_map(static function ($item, $index) {
        $qty = max(1, (int) ($item['qty'] ?? 1));
        $seatNo = max(1, (int) ($item['seat_no'] ?? 1));
        $split = strtoupper((string) ($item['split'] ?? 'A'));

        return [
            'itemId' => (string) ($item['item_id'] ?? $item['seq'] ?? sprintf('ROW-%03d', $index + 1)),
            'seq' => (string) ($item['seq'] ?? sprintf('%06d', ($index + 1) * 100)),
            'serviceCode' => (string) ($item['service_code'] ?? '-'),
            'description' => (string) ($item['description'] ?? '-'),
            'seatNo' => $seatNo,
            'qty' => $qty,
            'split' => $split,
            'splitQty' => max(1, min($qty, (int) ($item['split_qty'] ?? 1))),
        ];
    }, $items, array_keys($items));

    $initialPayload = [
        'tableNo' => $tableNo,
        'orderNo' => $orderNo,
        'currentZone' => 'A',
        'items' => array_values($normalizedInitialItems),
    ];
@endphp

<style>
    #{{ $id }}::backdrop {
        background: rgba(15, 23, 42, .45);
        backdrop-filter: blur(2px);
    }
</style>

<dialog
    id="{{ $id }}"
    class="m-auto w-[calc(100%-1.5rem)] max-w-6xl overflow-hidden rounded-[2px] border border-[#91aab2] bg-[#edf4f6] p-0 shadow-2xl"
>
    <div class="min-w-0">
        <div class="flex h-9 items-center justify-between border-b border-[#afc2c8] bg-[linear-gradient(180deg,#eaf5f8_0%,#d5e8ee_100%)] px-3">
            <div class="flex items-center gap-1.5">
                <span class="grid h-4 w-4 place-items-center rounded-[2px] border border-[#78a3b4] bg-[#d9f0f5] text-[9px] font-black text-[#326a7e]">
                    M
                </span>

                <h2 class="text-[11px] font-bold text-[#536b74]">
                    Move Split
                </h2>
            </div>

            <button
                type="button"
                data-close-move-split
                class="grid h-5 w-5 place-items-center rounded-[2px] text-[#6b7f87] transition hover:bg-[#c9e0e7] hover:text-[#244e5f]"
            >
                ✕
            </button>
        </div>

        <div class="border-b border-[#c5d4d8] bg-[#f4f8f9] px-4 py-3">
            <p class="mb-2 text-[10px] font-bold uppercase tracking-wide text-[#667c84]">
                Bill Info
            </p>

            <div class="grid max-w-md grid-cols-[110px_1fr] gap-x-3 gap-y-2">
                <label class="text-[10px] font-bold uppercase text-[#6b7e85]">
                    Table
                </label>

                <input
                    id="{{ $id }}TableNo"
                    type="text"
                    value="{{ $tableNo }}"
                    readonly
                    class="h-7 rounded-[2px] border border-[#c0d0d5] bg-[#e5edef] px-2 text-[10px] font-semibold text-[#64777e] outline-none"
                >

                <label class="text-[10px] font-bold uppercase text-[#6b7e85]">
                    Order #
                </label>

                <input
                    id="{{ $id }}OrderNo"
                    type="text"
                    value="{{ $orderNo }}"
                    readonly
                    class="h-7 rounded-[2px] border border-[#c0d0d5] bg-[#e5edef] px-2 text-[10px] font-semibold text-[#64777e] outline-none"
                >
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-[1fr_82px]">
            <section class="min-w-0 p-3">
                <div class="max-h-[430px] overflow-auto border border-[#a9bdc4] bg-white">
                    <table class="min-w-[830px] w-full border-collapse text-left text-[10px] text-[#516970]">
                        <thead class="sticky top-0 z-10 bg-[#dcecf0]">
                            <tr class="border-b border-[#b7cbd1]">
                                <th class="w-7 border-r border-[#c5d5d9] px-1 py-2"></th>
                                <th class="w-[95px] border-r border-[#c5d5d9] px-2 py-2 font-bold">Seq</th>
                                <th class="w-[135px] border-r border-[#c5d5d9] px-2 py-2 font-bold">Serv. Code</th>
                                <th class="border-r border-[#c5d5d9] px-2 py-2 font-bold">Description</th>
                                <th class="w-[75px] border-r border-[#c5d5d9] px-2 py-2 text-center font-bold">Seat #</th>
                                <th class="w-[70px] border-r border-[#c5d5d9] px-2 py-2 text-center font-bold">Qty</th>
                                <th class="w-[95px] border-r border-[#c5d5d9] px-2 py-2 text-center font-bold">Split</th>
                                <th class="w-[95px] px-2 py-2 text-center font-bold">Split Qty</th>
                            </tr>
                        </thead>

                        <tbody id="{{ $id }}Body"></tbody>
                    </table>
                </div>

                <p class="mt-2 text-[10px] text-[#70858c]">
                    Pilih baris item, tentukan tujuan split pada tombol sebelah kanan, lalu tekan Process.
                </p>
            </section>

            <aside class="border-t border-[#c5d4d8] bg-[#edf4f6] p-3 lg:border-l lg:border-t-0">
                <p class="mb-2 text-center text-[9px] font-bold uppercase tracking-wide text-[#6b8189]">
                    Split To
                </p>

                <div class="grid grid-cols-5 gap-1 lg:grid-cols-1">
                    @foreach (range('A', 'J') as $index => $zone)
                        <button
                            type="button"
                            data-split-zone="{{ $zone }}"
                            class="split-zone-button flex h-9 items-center justify-center rounded-[2px] border border-[#b6c9cf] bg-[#e1eef1] text-[10px] font-bold text-[#50747e] shadow-[inset_0_1px_0_rgba(255,255,255,.9)] transition hover:border-[#69a9c2] hover:bg-[#d3eaf0] hover:text-[#236884] {{ $index === 0 ? 'ring-2 ring-[#4d9dbd] ring-offset-1' : '' }}"
                        >
                            {{ $zone }}
                        </button>
                    @endforeach
                </div>
            </aside>
        </div>

        <div class="flex flex-wrap items-center justify-between gap-3 border-t border-[#c5d4d8] bg-[#edf4f6] px-4 py-3">
            <p id="{{ $id }}SelectedInfo" class="text-[10px] text-[#6d838a]">
                Tujuan split: <strong>A</strong>
            </p>

            <div class="flex items-center gap-2">
                <button
                    type="button"
                    id="{{ $id }}Process"
                    class="inline-flex h-8 min-w-[88px] items-center justify-center rounded-[2px] border border-[#7caebb] bg-[#e2f3f7] px-4 text-[10px] font-bold text-[#3d6e7e] shadow-[inset_0_1px_0_rgba(255,255,255,.95)] transition hover:border-[#4f97ae] hover:bg-[#d0eaf1]"
                >
                    PROCESS
                </button>

                <button
                    type="button"
                    data-close-move-split
                    class="inline-flex h-8 min-w-[88px] items-center justify-center rounded-[2px] border border-[#b2c1c5] bg-white px-4 text-[10px] font-bold text-[#61777f] shadow-[inset_0_1px_0_rgba(255,255,255,.95)] transition hover:bg-slate-100"
                >
                    CANCEL
                </button>
            </div>
        </div>
    </div>
</dialog>

<script>
    (() => {
        const modal = document.getElementById(@js($id));

        if (!modal || modal.dataset.ready === 'true') {
            return;
        }

        modal.dataset.ready = 'true';

        const initialPayload = @json($initialPayload);
        const body = document.getElementById(@js($id . 'Body'));
        const tableNoInput = document.getElementById(@js($id . 'TableNo'));
        const orderNoInput = document.getElementById(@js($id . 'OrderNo'));
        const processButton = document.getElementById(@js($id . 'Process'));
        const selectedInfo = document.getElementById(@js($id . 'SelectedInfo'));
        const zoneButtons = [...modal.querySelectorAll('[data-split-zone]')];

        const state = {
            tableNo: String(initialPayload.tableNo ?? ''),
            orderNo: String(initialPayload.orderNo ?? ''),
            currentZone: 'A',
            items: [],
            selectedItemId: null,
            selectedZone: 'A',
        };

        function escapeHtml(value = '') {
            return String(value).replace(/[&<>"']/g, (char) => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;',
            }[char]));
        }

        function normalizeZone(zone, fallback = 'A') {
            const normalized = String(zone ?? '').trim().toUpperCase();
            return zoneButtons.some((button) => button.dataset.splitZone === normalized)
                ? normalized
                : fallback;
        }

        function clampPositiveInteger(value, min = 1, max = Number.POSITIVE_INFINITY) {
            const parsed = Number.parseInt(value, 10);
            const normalized = Number.isFinite(parsed) ? parsed : min;

            return Math.min(Math.max(normalized, min), max);
        }

        function normalizeItems(items = []) {
            return (Array.isArray(items) ? items : []).map((item, index) => {
                const qty = clampPositiveInteger(item.qty ?? 1, 1);
                const sourceSplit = normalizeZone(item.sourceSplit ?? item.split ?? item.splitZone ?? 'A');
                const sourceSeatNo = clampPositiveInteger(item.sourceSeatNo ?? item.seatNo ?? item.seat_no ?? 1, 1);

                return {
                    itemId: String(item.itemId ?? item.item_id ?? item.uid ?? item.seq ?? `row-${index + 1}`),
                    seq: String(item.seq ?? `${(index + 1) * 100}`),
                    serviceCode: String(item.serviceCode ?? item.service_code ?? '-'),
                    description: String(item.description ?? item.name ?? '-'),
                    sourceSeatNo,
                    seatNo: clampPositiveInteger(item.seatNo ?? item.seat_no ?? sourceSeatNo, 1),
                    qty,
                    sourceSplit,
                    split: normalizeZone(item.split ?? item.splitZone ?? item.split_zone ?? sourceSplit, sourceSplit),
                    splitQty: clampPositiveInteger(item.splitQty ?? item.split_qty ?? 1, 1, qty),
                };
            }).sort((left, right) => Number(left.seq) - Number(right.seq));
        }

        function rows() {
            return [...body.querySelectorAll('[data-move-row]')];
        }

        function getItemById(itemId) {
            return state.items.find((item) => item.itemId === itemId) ?? null;
        }

        function getSelectedItem() {
            return getItemById(state.selectedItemId);
        }

        function updateProcessButtonState() {
            const disabled = !state.selectedItemId || state.items.length === 0;

            processButton.disabled = disabled;
            processButton.classList.toggle('opacity-50', disabled);
            processButton.classList.toggle('cursor-not-allowed', disabled);
        }

        function updateSelectedInfo() {
            const item = getSelectedItem();

            if (!item) {
                selectedInfo.textContent = 'Tidak ada item untuk dipindahkan.';
                return;
            }

            selectedInfo.innerHTML = `Tujuan split: <strong>${item.split}</strong>`;
        }

        function refreshZoneButtons() {
            zoneButtons.forEach((button) => {
                const isActive = button.dataset.splitZone === state.selectedZone;

                button.classList.toggle('ring-2', isActive);
                button.classList.toggle('ring-[#4d9dbd]', isActive);
                button.classList.toggle('ring-offset-1', isActive);
            });
        }

        function syncRowState(row) {
            const item = getItemById(row.dataset.itemId);

            if (!item) {
                return null;
            }

            const seatInput = row.querySelector('[data-seat-input]');
            const splitSelect = row.querySelector('[data-split-input]');
            const splitQtyInput = row.querySelector('[data-split-qty-input]');

            item.seatNo = clampPositiveInteger(seatInput?.value ?? item.seatNo, 1);
            item.split = normalizeZone(splitSelect?.value ?? item.split, item.sourceSplit);
            item.splitQty = clampPositiveInteger(splitQtyInput?.value ?? item.splitQty, 1, item.qty);

            if (seatInput) {
                seatInput.value = item.seatNo;
            }

            if (splitSelect) {
                splitSelect.value = item.split;
            }

            if (splitQtyInput) {
                splitQtyInput.max = String(item.qty);
                splitQtyInput.value = item.splitQty;
            }

            if (item.itemId === state.selectedItemId) {
                state.selectedZone = item.split;
                refreshZoneButtons();
                updateSelectedInfo();
            }

            return item;
        }

        function selectRow(itemId) {
            const item = getItemById(itemId);

            if (!item) {
                return;
            }

            state.selectedItemId = itemId;
            state.selectedZone = item.split;

            rows().forEach((row) => {
                const isActive = row.dataset.itemId === itemId;

                row.classList.toggle('bg-[#f5d69a]', isActive);
                row.classList.toggle('bg-white', !isActive);
                row.querySelector('[data-row-arrow]')?.classList.toggle('invisible', !isActive);
            });

            refreshZoneButtons();
            updateSelectedInfo();
            updateProcessButtonState();
        }

        function attachRowListeners() {
            rows().forEach((row) => {
                const itemId = row.dataset.itemId;
                const fields = row.querySelectorAll('input, select');

                row.addEventListener('click', () => {
                    selectRow(itemId);
                });

                fields.forEach((field) => {
                    field.addEventListener('click', (event) => {
                        event.stopPropagation();
                        selectRow(itemId);
                    });

                    field.addEventListener('input', () => {
                        selectRow(itemId);
                        syncRowState(row);
                    });

                    field.addEventListener('change', () => {
                        selectRow(itemId);
                        syncRowState(row);
                    });
                });
            });
        }

        function renderRows() {
            if (state.items.length === 0) {
                body.innerHTML = `
                    <tr>
                        <td colspan="8" class="px-3 py-10 text-center text-slate-400">
                            Belum ada item split bill yang bisa dipindahkan.
                        </td>
                    </tr>
                `;

                state.selectedItemId = null;
                state.selectedZone = state.currentZone;
                refreshZoneButtons();
                updateSelectedInfo();
                updateProcessButtonState();
                return;
            }

            if (!getSelectedItem()) {
                state.selectedItemId = state.items[0].itemId;
            }

            body.innerHTML = state.items.map((item) => `
                <tr
                    data-move-row
                    data-item-id="${escapeHtml(item.itemId)}"
                    class="cursor-pointer border-b border-[#d8e3e6] transition hover:bg-[#e7f3f6]"
                >
                    <td class="border-r border-[#e0e9eb] px-1 py-2 text-center">
                        <span data-row-arrow class="invisible text-[10px] font-bold text-[#5c6e73]">◆</span>
                    </td>
                    <td class="border-r border-[#e0e9eb] px-2 py-2 font-medium">${escapeHtml(item.seq)}</td>
                    <td class="border-r border-[#e0e9eb] px-2 py-2">${escapeHtml(item.serviceCode)}</td>
                    <td class="border-r border-[#e0e9eb] px-2 py-2 font-medium">${escapeHtml(item.description)}</td>
                    <td class="border-r border-[#e0e9eb] px-2 py-1 text-center">
                        <input
                            type="number"
                            min="1"
                            value="${item.seatNo}"
                            data-seat-input
                            class="h-6 w-12 rounded-[2px] border border-transparent bg-transparent text-center text-[10px] outline-none focus:border-[#77b6ce] focus:bg-white"
                        >
                    </td>
                    <td class="border-r border-[#e0e9eb] px-2 py-1 text-center">
                        <input
                            type="number"
                            value="${item.qty}"
                            readonly
                            data-qty-input
                            class="h-6 w-12 rounded-[2px] border border-transparent bg-[#f8fbfc] text-center text-[10px] text-[#516970] outline-none"
                        >
                    </td>
                    <td class="border-r border-[#e0e9eb] px-2 py-1 text-center">
                        <select
                            data-split-input
                            class="h-6 w-16 rounded-[2px] border border-transparent bg-transparent text-center text-[10px] outline-none focus:border-[#77b6ce] focus:bg-white"
                        >
                            ${zoneButtons.map((button) => `
                                <option value="${button.dataset.splitZone}" ${button.dataset.splitZone === item.split ? 'selected' : ''}>
                                    ${button.dataset.splitZone}
                                </option>
                            `).join('')}
                        </select>
                    </td>
                    <td class="px-2 py-1 text-center">
                        <input
                            type="number"
                            min="1"
                            max="${item.qty}"
                            value="${item.splitQty}"
                            data-split-qty-input
                            class="h-6 w-14 rounded-[2px] border border-transparent bg-transparent text-center text-[10px] outline-none focus:border-[#77b6ce] focus:bg-white"
                        >
                    </td>
                </tr>
            `).join('');

            attachRowListeners();
            selectRow(state.selectedItemId);
        }

        function populate(payload = {}) {
            state.tableNo = String(payload.tableNo ?? initialPayload.tableNo ?? '').trim() || String(initialPayload.tableNo ?? '');
            state.orderNo = String(payload.orderNo ?? initialPayload.orderNo ?? '').trim() || String(initialPayload.orderNo ?? '');
            state.currentZone = normalizeZone(payload.currentZone ?? initialPayload.currentZone ?? 'A');
            state.items = normalizeItems(Array.isArray(payload.items) ? payload.items : initialPayload.items);
            state.selectedItemId = state.items[0]?.itemId ?? null;
            state.selectedZone = state.items[0]?.split ?? state.currentZone;

            if (tableNoInput) {
                tableNoInput.value = state.tableNo;
            }

            if (orderNoInput) {
                orderNoInput.value = state.orderNo;
            }

            renderRows();
        }

        function selectZone(zone) {
            state.selectedZone = normalizeZone(zone, state.selectedZone);

            const selectedRow = rows().find((row) => row.dataset.itemId === state.selectedItemId);

            if (!selectedRow) {
                refreshZoneButtons();
                updateSelectedInfo();
                return;
            }

            const splitSelect = selectedRow.querySelector('[data-split-input]');

            if (splitSelect) {
                splitSelect.value = state.selectedZone;
            }

            syncRowState(selectedRow);
        }

        zoneButtons.forEach((button) => {
            button.addEventListener('click', () => {
                selectZone(button.dataset.splitZone);
            });
        });

        modal.querySelectorAll('[data-close-move-split]').forEach((button) => {
            button.addEventListener('click', () => {
                modal.close();
            });
        });

        processButton.addEventListener('click', () => {
            const selectedRow = rows().find((row) => row.dataset.itemId === state.selectedItemId);

            if (!selectedRow) {
                window.alert('Pilih item yang ingin dipindahkan terlebih dahulu.');
                return;
            }

            const item = syncRowState(selectedRow);

            if (!item) {
                return;
            }

            if (!item.splitQty || item.splitQty < 1 || item.splitQty > item.qty) {
                window.alert('Split Qty harus minimal 1 dan tidak boleh melebihi Qty.');
                return;
            }

            if (item.sourceSplit === item.split && Number(item.sourceSeatNo) === Number(item.seatNo)) {
                window.alert('Pilih split atau seat tujuan yang berbeda terlebih dahulu.');
                return;
            }

            window.dispatchEvent(new CustomEvent('move-split-processed', {
                detail: {
                    tableNo: state.tableNo,
                    orderNo: state.orderNo,
                    itemId: item.itemId,
                    seq: item.seq,
                    serviceCode: item.serviceCode,
                    description: item.description,
                    sourceSplit: item.sourceSplit,
                    sourceSeatNo: item.sourceSeatNo,
                    destination: item.split,
                    destinationSeatNo: item.seatNo,
                    qty: item.qty,
                    splitQty: item.splitQty,
                },
            }));

            modal.close();
        });

        modal.addEventListener('click', (event) => {
            if (event.target === modal) {
                modal.close();
            }
        });

        window.addEventListener('move-split:prepare', (event) => {
            populate(event.detail || {});
        });

        populate(initialPayload);
    })();
</script>
