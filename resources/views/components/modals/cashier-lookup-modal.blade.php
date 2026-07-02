@props([
    'id' => 'cashierLookupModal',
    'cashiers' => [
        [
            'cashier_id' => 'FBA-ADHA',
            'name' => 'Adha',
            'shift' => '1',
        ],
        [
            'cashier_id' => 'FBA-FAR',
            'name' => 'Farhan',
            'shift' => '1',
        ],
        [
            'cashier_id' => 'FBA-LISA',
            'name' => 'Lisa',
            'shift' => '1',
        ],
    ],
])

<style>
    #{{ $id }}::backdrop {
        background: rgba(15, 23, 42, .45);
        backdrop-filter: blur(2px);
    }
</style>

<dialog
    id="{{ $id }}"
    class="m-auto w-[calc(100%-1.5rem)] max-w-4xl overflow-hidden rounded-[2px] border border-[#8fa6ae] bg-[#edf4f6] p-0 shadow-2xl"
>
    <div class="min-w-0">

        {{-- Header --}}
        <div class="flex h-9 items-center justify-between border-b border-[#afc2c8] bg-[linear-gradient(180deg,#eaf5f8_0%,#d5e8ee_100%)] px-3">
            <div class="flex items-center gap-1.5">
                <span class="grid h-4 w-4 place-items-center rounded-[2px] border border-[#78a3b4] bg-[#d9f0f5] text-[9px] font-black text-[#326a7e]">
                    C
                </span>

                <h2 class="text-[11px] font-bold text-[#536b74]">
                    Look up Cashier
                </h2>
            </div>

            <button
                type="button"
                data-close-cashier-modal
                class="grid h-5 w-5 place-items-center rounded-[2px] text-[#6b7f87] transition hover:bg-[#c9e0e7] hover:text-[#244e5f]"
            >
                ✕
            </button>
        </div>

        {{-- Tabel --}}
        <div class="p-3">
            <div class="max-h-[430px] overflow-auto border border-[#a9bdc4] bg-white">
                <table class="min-w-[650px] w-full border-collapse text-left text-[10px] text-[#526b74]">
                    <thead class="sticky top-0 z-10 bg-[#dcecf0]">
                        <tr class="border-b border-[#b7cbd1]">
                            <th class="w-8 border-r border-[#c5d5d9] px-1 py-2"></th>

                            <th class="w-[210px] border-r border-[#c5d5d9] px-3 py-2 font-bold">
                                <button
                                    type="button"
                                    data-sort-key="cashierId"
                                    class="flex items-center gap-1 hover:text-[#216984]"
                                >
                                    Cashier ID
                                    <span class="text-[8px]">▼</span>
                                </button>
                            </th>

                            <th class="border-r border-[#c5d5d9] px-3 py-2 font-bold">
                                <button
                                    type="button"
                                    data-sort-key="name"
                                    class="flex items-center gap-1 hover:text-[#216984]"
                                >
                                    Name
                                    <span class="text-[8px]">▼</span>
                                </button>
                            </th>

                            <th class="w-[180px] px-3 py-2 font-bold">
                                <button
                                    type="button"
                                    data-sort-key="shift"
                                    class="flex items-center gap-1 hover:text-[#216984]"
                                >
                                    Shift
                                    <span class="text-[8px]">▼</span>
                                </button>
                            </th>
                        </tr>
                    </thead>

                    <tbody id="{{ $id }}Body">
                        @foreach ($cashiers as $index => $cashier)
                            <tr
                                data-cashier-row
                                data-cashier-id="{{ $cashier['cashier_id'] }}"
                                data-name="{{ $cashier['name'] }}"
                                data-shift="{{ $cashier['shift'] }}"
                                class="cursor-pointer border-b border-[#d7e3e6] transition hover:bg-[#e7f3f6] {{ $index === 0 ? 'bg-[#f6d69a]' : 'bg-white' }}"
                            >
                                <td class="border-r border-[#e0e9eb] px-1 py-2 text-center">
                                    <span
                                        data-row-arrow
                                        class="{{ $index === 0 ? '' : 'invisible' }} text-[10px] font-bold text-[#5c6e73]"
                                    >
                                        ◆
                                    </span>
                                </td>

                                <td class="border-r border-[#e0e9eb] px-3 py-2 font-medium">
                                    {{ $cashier['cashier_id'] }}
                                </td>

                                <td class="border-r border-[#e0e9eb] px-3 py-2">
                                    {{ $cashier['name'] }}
                                </td>

                                <td class="px-3 py-2">
                                    {{ $cashier['shift'] }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div
                id="{{ $id }}Empty"
                class="hidden border border-dashed border-[#b9cbd1] bg-white px-4 py-10 text-center text-xs text-slate-400"
            >
                Data cashier tidak ditemukan.
            </div>
        </div>

        {{-- Footer --}}
        <div class="flex flex-wrap items-center justify-between gap-3 border-t border-[#c5d4d8] bg-[#edf4f6] px-4 py-3">
            <p id="{{ $id }}Info" class="text-[10px] text-[#6d838a]">
                Pilih cashier untuk melanjutkan.
            </p>

            <div class="flex items-center gap-2">
                <button
                    type="button"
                    id="{{ $id }}Ok"
                    class="inline-flex h-8 min-w-[82px] items-center justify-center rounded-[2px] border border-[#89acb8] bg-[#e2f3f7] px-4 text-[10px] font-bold text-[#3d6e7e] shadow-[inset_0_1px_0_rgba(255,255,255,.95)] transition hover:border-[#4f97ae] hover:bg-[#d0eaf1]"
                >
                    OK
                </button>

                <button
                    type="button"
                    data-close-cashier-modal
                    class="inline-flex h-8 min-w-[82px] items-center justify-center rounded-[2px] border border-[#b3c3c7] bg-white px-4 text-[10px] font-bold text-[#60767e] shadow-[inset_0_1px_0_rgba(255,255,255,.95)] transition hover:bg-slate-100"
                >
                    Cancel
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

        const body = document.getElementById(@js($id . 'Body'));
        const info = document.getElementById(@js($id . 'Info'));
        const okButton = document.getElementById(@js($id . 'Ok'));

        let selectedRow = modal.querySelector('[data-cashier-row]');
        let sortState = {
            key: null,
            direction: 'asc'
        };

        function getRows() {
            return [...modal.querySelectorAll('[data-cashier-row]')];
        }

        function selectRow(row) {
            getRows().forEach((item) => {
                item.classList.remove('bg-[#f6d69a]');
                item.classList.add('bg-white');

                const arrow = item.querySelector('[data-row-arrow]');

                if (arrow) {
                    arrow.classList.add('invisible');
                }
            });

            row.classList.remove('bg-white');
            row.classList.add('bg-[#f6d69a]');

            const arrow = row.querySelector('[data-row-arrow]');

            if (arrow) {
                arrow.classList.remove('invisible');
            }

            selectedRow = row;

            info.innerHTML = `
                Cashier dipilih:
                <strong>${row.dataset.cashierId}</strong>
                - ${row.dataset.name}
            `;
        }

        function sortRows(key) {
            const direction =
                sortState.key === key && sortState.direction === 'asc'
                    ? 'desc'
                    : 'asc';

            const rows = getRows().sort((a, b) => {
                const valueA = (a.dataset[key] || '').toLowerCase();
                const valueB = (b.dataset[key] || '').toLowerCase();

                return direction === 'asc'
                    ? valueA.localeCompare(valueB, 'id', { numeric: true })
                    : valueB.localeCompare(valueA, 'id', { numeric: true });
            });

            rows.forEach((row) => {
                body.appendChild(row);
            });

            sortState = {
                key,
                direction
            };
        }

        getRows().forEach((row) => {
            row.addEventListener('click', () => {
                selectRow(row);
            });

            row.addEventListener('dblclick', () => {
                selectRow(row);
                okButton.click();
            });
        });

        modal.querySelectorAll('[data-sort-key]').forEach((button) => {
            button.addEventListener('click', () => {
                sortRows(button.dataset.sortKey);
            });
        });

        modal.querySelectorAll('[data-close-cashier-modal]').forEach((button) => {
            button.addEventListener('click', () => {
                modal.close();
            });
        });

        okButton.addEventListener('click', () => {
            if (!selectedRow) {
                alert('Pilih cashier terlebih dahulu.');
                return;
            }

            const cashier = {
                cashierId: selectedRow.dataset.cashierId,
                name: selectedRow.dataset.name,
                shift: selectedRow.dataset.shift,
            };

            window.dispatchEvent(
                new CustomEvent('cashier-selected', {
                    detail: cashier
                })
            );

            modal.close();
        });

        modal.addEventListener('click', (event) => {
            if (event.target === modal) {
                modal.close();
            }
        });

        if (selectedRow) {
            selectRow(selectedRow);
        }

       

    })();
</script>
