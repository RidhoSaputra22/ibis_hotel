@props([
    'id' => 'reservationFolioLookupModal',
    'reservations' => [
        [
            'resv_no' => '0000016821',
            'name' => 'WAKHIDIN',
            'folio_no' => '0000015944',
            'pos_chg' => true,
            'room_no' => '119',
        ],
        [
            'resv_no' => '0000055367',
            'name' => 'PETER UNTERBERGER',
            'folio_no' => '0000059711',
            'pos_chg' => true,
            'room_no' => '712',
        ],
        [
            'resv_no' => '0000056114',
            'name' => 'MARTHA ENDOKA SASONGKO',
            'folio_no' => '0000055628',
            'pos_chg' => false,
            'room_no' => '702',
        ],
        [
            'resv_no' => '0000056872',
            'name' => 'MEGA / AGUS (BN 10291)',
            'folio_no' => '0000055062',
            'pos_chg' => true,
            'room_no' => '501',
        ],
        [
            'resv_no' => '0000057148',
            'name' => 'KARINA DIYAH AYU RATNA',
            'folio_no' => '0000055720',
            'pos_chg' => false,
            'room_no' => '717',
        ],
        [
            'resv_no' => '0000057151',
            'name' => 'ASRUL ARSYAL (BN 11328)',
            'folio_no' => '0000055379',
            'pos_chg' => true,
            'room_no' => '508',
        ],
        [
            'resv_no' => '0000057152',
            'name' => 'BN 6112 ARYAN ELIAS PALODIO',
            'folio_no' => '0000055574',
            'pos_chg' => true,
            'room_no' => '810',
        ],
    ],
])

<style>
    #{{ $id }}::backdrop {
        background: rgba(15, 23, 42, .48);
        backdrop-filter: blur(2px);
    }
</style>


<dialog
    id="{{ $id }}"
    class="m-auto w-[calc(100%-1.5rem)] max-w-6xl overflow-hidden rounded-[2px] border border-[#8fa6ae] bg-[#edf4f6] p-0 shadow-2xl"
>
    <div class="min-w-0">

        {{-- Header Modal --}}
        <div class="flex h-9 items-center justify-between border-b border-[#aec0c6] bg-[linear-gradient(180deg,#eaf5f8_0%,#d5e8ee_100%)] px-3">
            <div class="flex items-center gap-1.5">
                <span class="grid h-4 w-4 place-items-center rounded-[2px] border border-[#78a3b4] bg-[#d9f0f5] text-[9px] font-black text-[#326a7e]">
                    R
                </span>

                <h2 class="text-[11px] font-bold text-[#536b74]">
                    Folio Lookup
                </h2>
            </div>

            <button
                type="button"
                data-close-modal
                class="grid h-5 w-5 place-items-center rounded-[2px] text-[#6a7f87] transition hover:bg-[#c7e0e7] hover:text-[#254e60]"
            >
                ✕
            </button>
        </div>

        {{-- Tabel --}}
        <div class="p-3 pb-2">
            <div class="max-h-[min(58vh,460px)] overflow-auto border border-[#a7bdc4] bg-white">
                <table class="min-w-[760px] w-full border-collapse text-left text-[10px] text-[#526b74]">

                    <thead class="sticky top-0 z-20">

                        {{-- Header tabel --}}
                        <tr class="bg-[#dcecf0]">
                            <th class="w-7 border-b border-r border-[#bdced3] px-1 py-2"></th>

                            <th class="w-[155px] border-b border-r border-[#bdced3] px-2 py-2.5 font-bold">
                                <button type="button" data-sort="resvNo">
                                    Resv. No ▼
                                </button>
                            </th>

                            <th class="border-b border-r border-[#bdced3] px-2 py-2.5 font-bold">
                                <button type="button" data-sort="name">
                                    Name ▼
                                </button>
                            </th>

                            <th class="w-[150px] border-b border-r border-[#bdced3] px-2 py-2.5 font-bold">
                                <button type="button" data-sort="folioNo">
                                    Folio No ▼
                                </button>
                            </th>

                            <th class="w-[75px] border-b border-r border-[#bdced3] px-2 py-2.5 text-center font-bold">
                                Pos Chg
                            </th>

                            <th class="w-[105px] border-b border-[#bdced3] px-2 py-2.5 font-bold">
                                <button type="button" data-sort="roomNo">
                                    Room No ▼
                                </button>
                            </th>
                        </tr>

                        {{-- Filter --}}
                        <tr class="bg-[#edf5f7] text-[#687f87]">
                            <th class="border-b border-r border-[#cfdee2]"></th>

                            <th class="border-b border-r border-[#cfdee2] px-2 py-1.5">
                                <input
                                    type="search"
                                    data-filter="resvNo"
                                    placeholder="Contains"
                                    class="h-6 w-full border-0 bg-transparent text-[10px] outline-none placeholder:text-[#728a92]"
                                >
                            </th>

                            <th class="border-b border-r border-[#cfdee2] px-2 py-1.5">
                                <input
                                    type="search"
                                    data-filter="name"
                                    placeholder="Contains"
                                    class="h-6 w-full border-0 bg-transparent text-[10px] outline-none placeholder:text-[#728a92]"
                                >
                            </th>

                            <th class="border-b border-r border-[#cfdee2] px-2 py-1.5">
                                <input
                                    type="search"
                                    data-filter="folioNo"
                                    placeholder="Contains"
                                    class="h-6 w-full border-0 bg-transparent text-[10px] outline-none placeholder:text-[#728a92]"
                                >
                            </th>

                            <th class="border-b border-r border-[#cfdee2] px-2 py-1.5 text-center">
                                <input
                                    type="checkbox"
                                    id="{{ $id }}PosFilter"
                                    class="h-3.5 w-3.5 rounded border-slate-300 text-sky-600"
                                >
                            </th>

                            <th class="border-b border-[#cfdee2] px-2 py-1.5">
                                <input
                                    type="search"
                                    data-filter="roomNo"
                                    placeholder="Contains"
                                    class="h-6 w-full border-0 bg-transparent text-[10px] outline-none placeholder:text-[#728a92]"
                                >
                            </th>
                        </tr>
                    </thead>

                    <tbody id="{{ $id }}Body">
                        @foreach ($reservations as $index => $reservation)
                            <tr
                                data-row
                                data-resv-no="{{ $reservation['resv_no'] }}"
                                data-name="{{ $reservation['name'] }}"
                                data-folio-no="{{ $reservation['folio_no'] }}"
                                data-pos-chg="{{ $reservation['pos_chg'] ? '1' : '0' }}"
                                data-room-no="{{ $reservation['room_no'] }}"
                                class="cursor-pointer border-b border-[#d6e2e5] transition hover:bg-[#e7f3f6] {{ $index === 0 ? 'bg-[#f6d59b]' : 'bg-white' }}"
                            >
                                <td class="border-r border-[#e0e9eb] px-1 py-2 text-center">
                                    <span data-arrow class="{{ $index === 0 ? '' : 'invisible' }}">
                                        ◆
                                    </span>
                                </td>

                                <td class="border-r border-[#e0e9eb] px-2 py-2 font-medium">
                                    {{ $reservation['resv_no'] }}
                                </td>

                                <td class="border-r border-[#e0e9eb] px-2 py-2">
                                    {{ $reservation['name'] }}
                                </td>

                                <td class="border-r border-[#e0e9eb] px-2 py-2">
                                    {{ $reservation['folio_no'] }}
                                </td>

                                <td class="border-r border-[#e0e9eb] px-2 py-1.5 text-center">
                                    <input
                                        type="checkbox"
                                        data-pos-checkbox
                                        @checked($reservation['pos_chg'])
                                        class="h-3.5 w-3.5 rounded border-slate-300 text-sky-600"
                                    >
                                </td>

                                <td class="px-2 py-2">
                                    {{ $reservation['room_no'] }}
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
                Data reservasi tidak ditemukan.
            </div>
        </div>

        {{-- Footer --}}
        <div class="flex flex-wrap items-center justify-between gap-3 border-t border-[#c4d3d7] bg-[#edf4f6] px-4 py-3">
            <p id="{{ $id }}Summary" class="text-[10px] text-[#6e838a]">
                {{ count($reservations) }} data reservasi tersedia.
            </p>

            <div class="flex items-center gap-2">
                <button
                    type="button"
                    id="{{ $id }}Ok"
                    class="inline-flex h-7 min-w-[68px] items-center justify-center rounded-[2px] border border-[#89acb8] bg-[#eaf7fa] px-3 text-[10px] font-bold text-[#48727f] hover:bg-[#dceff4]"
                >
                    OK
                </button>

                <button
                    type="button"
                    data-close-modal
                    class="inline-flex h-7 min-w-[68px] items-center justify-center rounded-[2px] border border-[#b4c2c6] bg-white px-3 text-[10px] font-bold text-[#63777e] hover:bg-slate-100"
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
        const summary = document.getElementById(@js($id . 'Summary'));
        const emptyMessage = document.getElementById(@js($id . 'Empty'));
        const posFilter = document.getElementById(@js($id . 'PosFilter'));
        const okButton = document.getElementById(@js($id . 'Ok'));

        const filterInputs = [
            ...modal.querySelectorAll('[data-filter]')
        ];

        let selectedRow = modal.querySelector('[data-row]');
        let sortState = {
            key: null,
            direction: 'asc'
        };

        function getRows() {
            return [...modal.querySelectorAll('[data-row]')];
        }

        function selectRow(row) {
            getRows().forEach((item) => {
                item.classList.remove('bg-[#f6d59b]');
                item.classList.add('bg-white');

                const arrow = item.querySelector('[data-arrow]');
                if (arrow) {
                    arrow.classList.add('invisible');
                }
            });

            row.classList.remove('bg-white');
            row.classList.add('bg-[#f6d59b]');

            const arrow = row.querySelector('[data-arrow]');
            if (arrow) {
                arrow.classList.remove('invisible');
            }

            selectedRow = row;
        }

        function filterRows() {
            const filters = {};

            filterInputs.forEach((input) => {
                filters[input.dataset.filter] = input.value.trim().toLowerCase();
            });

            let visibleCount = 0;

            getRows().forEach((row) => {
                const resvNo = row.dataset.resvNo.toLowerCase();
                const name = row.dataset.name.toLowerCase();
                const folioNo = row.dataset.folioNo.toLowerCase();
                const roomNo = row.dataset.roomNo.toLowerCase();
                const posChg = row.dataset.posChg;

                const matchResv =
                    !filters.resvNo ||
                    resvNo.includes(filters.resvNo);

                const matchName =
                    !filters.name ||
                    name.includes(filters.name);

                const matchFolio =
                    !filters.folioNo ||
                    folioNo.includes(filters.folioNo);

                const matchRoom =
                    !filters.roomNo ||
                    roomNo.includes(filters.roomNo);

                const matchPos =
                    !posFilter.checked ||
                    posChg === '1';

                const visible =
                    matchResv &&
                    matchName &&
                    matchFolio &&
                    matchRoom &&
                    matchPos;

                row.classList.toggle('hidden', !visible);

                if (visible) {
                    visibleCount++;
                }
            });

            emptyMessage.classList.toggle('hidden', visibleCount > 0);
            summary.textContent = `${visibleCount} data reservasi ditemukan.`;
        }

        function sortRows(key) {
            const direction =
                sortState.key === key && sortState.direction === 'asc'
                    ? 'desc'
                    : 'asc';

            const sortedRows = getRows().sort((a, b) => {
                const valueA = (a.dataset[key] || '').toLowerCase();
                const valueB = (b.dataset[key] || '').toLowerCase();

                return direction === 'asc'
                    ? valueA.localeCompare(valueB, 'id', { numeric: true })
                    : valueB.localeCompare(valueA, 'id', { numeric: true });
            });

            sortedRows.forEach((row) => body.appendChild(row));

            sortState = {
                key,
                direction
            };
        }

        getRows().forEach((row) => {
            row.addEventListener('click', (event) => {
                if (event.target.matches('[data-pos-checkbox]')) {
                    row.dataset.posChg = event.target.checked ? '1' : '0';
                    filterRows();
                    return;
                }

                selectRow(row);
            });

            row.addEventListener('dblclick', () => {
                selectRow(row);
                okButton.click();
            });
        });

        modal.querySelectorAll('[data-close-modal]').forEach((button) => {
            button.addEventListener('click', () => {
                modal.close();
            });
        });

        modal.querySelectorAll('[data-sort]').forEach((button) => {
            button.addEventListener('click', () => {
                sortRows(button.dataset.sort);
            });
        });

        filterInputs.forEach((input) => {
            input.addEventListener('input', filterRows);
        });

        posFilter.addEventListener('change', filterRows);

        okButton.addEventListener('click', () => {
            if (!selectedRow) {
                alert('Pilih data reservasi terlebih dahulu.');
                return;
            }

            const result = {
                reservationNo: selectedRow.dataset.resvNo,
                name: selectedRow.dataset.name,
                folioNo: selectedRow.dataset.folioNo,
                posCharge: selectedRow.dataset.posChg === '1',
                roomNo: selectedRow.dataset.roomNo,
            };

            window.dispatchEvent(
                new CustomEvent('reservation-folio-selected', {
                    detail: result
                })
            );

            modal.close();
        });

        modal.addEventListener('click', (event) => {
            if (event.target === modal) {
                modal.close();
            }
        });

     

    })();
</script>
